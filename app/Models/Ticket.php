<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference',
        'client_id',
        'client_site_id',
        'machine_id',
        'ticket_category_id',
        'ticket_priority_id',
        'ticket_status_id',
        'user_id',       // 👈 ضروري نزيدوه هنا
        'created_by',    // 👈 ضروري نزيدوه هنا
        'assigned_to',
        'titre',
        'description',
        'source',
        'date_echeance_sla',
        'date_premiere_reponse',
        'date_resolution',
        'date_cloture',
        'note_satisfaction',
    ];

    protected $casts = [
        'date_echeance_sla'     => 'datetime',
        'date_premiere_reponse' => 'datetime',
        'date_resolution'       => 'datetime',
        'date_cloture'          => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function site()
    {
        return $this->belongsTo(ClientSite::class, 'client_site_id');
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function category()
    {
        return $this->belongsTo(TicketCategory::class, 'ticket_category_id');
    }

    public function priority()
    {
        return $this->belongsTo(TicketPriority::class, 'ticket_priority_id');
    }

    public function status()
    {
        return $this->belongsTo(TicketStatus::class, 'ticket_status_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }

    public function histories()
    {
        return $this->hasMany(TicketStatusHistory::class);
    }
    // علاقة مع المستخدم اللي صاوب التيكيت (إيلا كان مسجل بـ user_id)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // علاقة مع منشئ التيكيت (إيلا كنتي مستعمل created_by)
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }
}