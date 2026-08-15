<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewMessageNotification;

class ChatController extends Controller
{
    /**
     * Afficher la liste des conversations et les utilisateurs
     */
    public function index()
    {
        $userId = Auth::id();
        $authUser = Auth::user();
        
        $conversations = Conversation::where('user_one_id', $userId)
            ->orWhere('user_two_id', $userId)
            ->with(['userOne', 'userTwo', 'messages' => function($q) {
                $q->latest()->limit(1);
            }])
            ->get();

        $isClient = $authUser->hasRole('Client') || $authUser->hasRole('client') || (isset($authUser->role) && strtolower($authUser->role) === 'client');

        if ($isClient) {
            $contactIds = $conversations->map(function($conv) use ($userId) {
                return $conv->user_one_id == $userId ? $conv->user_two_id : $conv->user_one_id;
            })->unique();

            $users = $contactIds->isNotEmpty() ? User::whereIn('id', $contactIds)->get() : collect();
        } else {
            $users = User::where('id', '!=', $userId)->get();
        }

        return view('chat.index', compact('conversations', 'users'));
    }

    /**
     * Afficher ou creer une conversation m3a user m3yn
     */
    public function show(User $user)
    {
        $authId = Auth::id();
        $authUser = Auth::user();

        if ($authId == $user->id) {
            return redirect()->route('chat.index');
        }
        
        $isClient = $authUser->hasRole('Client') || $authUser->hasRole('client') || (isset($authUser->role) && strtolower($authUser->role) === 'client');
        
        $conversation = Conversation::where(function($q) use ($authId, $user) {
            $q->where('user_one_id', $authId)->where('user_two_id', $user->id);
        })->orWhere(function($q) use ($authId, $user) {
            $q->where('user_one_id', $user->id)->where('user_two_id', $authId);
        })->first();

        if (!$conversation) {
            if ($isClient) {
                return redirect()->route('chat.index');
            }
            $conversation = Conversation::create([
                'user_one_id' => $authId,
                'user_two_id' => $user->id,
                'is_blocked' => false,
                'blocked_by' => null
            ]);
        }

        foreach ($authUser->unreadNotifications as $notification) {
            if (isset($notification->data['sender_id']) && $notification->data['sender_id'] == $user->id) {
                $notification->markAsRead();
            }
        }

        $messages = $conversation ? $conversation->messages()->with('user')->get() : collect();
        
        $conversations = Conversation::where('user_one_id', $authId)
            ->orWhere('user_two_id', $authId)
            ->get();
            
        if ($isClient) {
            $contactIds = $conversations->map(function($conv) use ($authId) {
                return $conv->user_one_id == $authId ? $conv->user_two_id : $conv->user_one_id;
            })->unique();

            $users = $contactIds->isNotEmpty() ? User::whereIn('id', $contactIds)->get() : collect();
        } else {
            $users = User::where('id', '!=', $authId)->get();
        }

        return view('chat.index', compact('conversation', 'messages', 'conversations', 'users', 'user'));
    }

    /**
     * Store Message (Text, Fichiers, Audio)
     */
    public function store(Request $request, Conversation $conversation)
    {
        $authId = Auth::id();

        // التأكد واش المحادثة مبلۆكية
        if ($conversation->is_blocked) {
            return back()->with('error', 'Impossible d\'envoyer un message, cette conversation est bloquée.');
        }

        $request->validate([
            'body' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:10240',
            'audio' => 'nullable|file|mimes:audio/mpeg,mp3,wav,ogg,webm|max:10240',
        ]);

        $filePath = null;
        $fileType = null;
        $originalName = null;

        // Ila kan fichier (image / document)
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $originalName = $file->getClientOriginalName();
            $filePath = $file->store('chat-attachments', 'public');
            $mime = $file->getMimeType();
            $fileType = str_starts_with($mime, 'image/') ? 'image' : 'document';
        }

        // Ila kan audio (voice note)
        if ($request->hasFile('audio')) {
            $file = $request->file('audio');
            $originalName = $file->getClientOriginalName();
            $filePath = $file->store('chat-audio', 'public');
            $fileType = 'audio';
        }

        // Vérification de sécurité : ila kan kolchi khawi (la text, la fichier, la audio)
        if (!$request->filled('body') && !$filePath) {
            return back()->with('error', 'Le message ne peut pas être vide.');
        }

        $message = $conversation->messages()->create([
            'user_id' => $authId,
            'body' => $request->body ?? '',
            'file_path' => $filePath,
            'file_type' => $fileType,
            'original_name' => $originalName,
        ]);

        $receiverId = ($conversation->user_one_id == $authId) ? $conversation->user_two_id : $conversation->user_one_id;
        $receiver = User::find($receiverId);

        if ($receiver) {
            $receiver->notify(new NewMessageNotification($message));
        }

        return back();
    }

    /**
     * Bloquer / Débloquer la conversation
     */
    public function toggleBlock(User $user)
    {
        $authId = Auth::id();

        $conversation = Conversation::where(function($q) use ($authId, $user) {
            $q->where('user_one_id', $authId)->where('user_two_id', $user->id);
        })->orWhere(function($q) use ($authId, $user) {
            $q->where('user_one_id', $user->id)->where('user_two_id', $authId);
        })->first();

        if (!$conversation) {
            return back()->with('error', 'Conversation introuvable.');
        }

        if ($conversation->is_blocked) {
            if ($conversation->blocked_by != $authId) {
                return back()->with('error', 'Vous ne pouvez pas débloquer une conversation que vous n\'avez pas bloquée.');
            }

            $conversation->is_blocked = false;
            $conversation->blocked_by = null;
            $status = 'débloquée';
        } else {
            $conversation->is_blocked = true;
            $conversation->blocked_by = $authId;
            $status = 'bloquée';
        }
        
        $conversation->save();

        return back()->with('success', "La conversation a été {$status} avec succès.");
    }

    /**
     * Clôturer : Supprime uniquement les messages, garde la conversation pour maintenir le blocage
     */
    public function destroy(Conversation $conversation)
    {
        $authId = Auth::id();

        if ($conversation->user_one_id != $authId && $conversation->user_two_id != $authId) {
            abort(403);
        }

        // نمسحو غير الميساجات، ونخليو الكونسيرساصيو باش يبقى فيها الـ is_blocked
        $conversation->messages()->delete();

        return redirect()->route('chat.index')->with('success', 'Les messages ont été supprimés, mais la conversation est maintenue.');
    }
}