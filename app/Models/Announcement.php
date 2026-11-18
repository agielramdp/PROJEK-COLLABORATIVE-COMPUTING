<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $table = 'announcements';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'target_role',
        'priority',
        'author_id',
        'is_published',
        'published_at',
        'expires_at',
        'attachment_path',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'expires_at'   => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope untuk pengumuman aktif dan relevan untuk role tertentu
     */
    public function scopeActiveForRole($query, ?string $role = null)
    {
        $now = Carbon::now();

        $query->where('is_published', true)
              ->where(function ($q) use ($now) {
                  $q->whereNull('published_at')->orWhere('published_at', '<=', $now);
              })
              ->where(function ($q) use ($now) {
                  $q->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
              });

        if ($role && strtolower($role) !== 'admin') {
            $query->where(function ($q) use ($role) {
                $q->where('target_role', 'Semua')
                  ->orWhere('target_role', $role);
            });
        }

        return $query->orderBy('priority', 'desc')->orderBy('created_at', 'desc');
    }
}
