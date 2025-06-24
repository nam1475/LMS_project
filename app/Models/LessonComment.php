<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonComment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'lesson_id',
        'comment',
        'parent_id'
    ];

    public function lesson()
    {
        return $this->belongsTo(CourseChapterLession::class);
    }

    public function commentator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parentComment()
    {
        return $this->belongsTo(LessonComment::class, 'parent_id');
    }

    public function childComments()
    {
        return $this->hasMany(LessonComment::class, 'parent_id')->with('commentator')->orderBy('created_at', 'asc');
    }

    // public function nestedChildCommentators()
    // {
    //     return $this->childComments()->with('commentator')->get();
    // }
}
