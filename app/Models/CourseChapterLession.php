<?php

namespace App\Models;

use App\Traits\Draft;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Oddvalue\LaravelDrafts\Concerns\HasDrafts;

class CourseChapterLession extends Model
{
    use HasFactory, HasDrafts, Draft;

    protected $fillable = [
        'title',
        'slug',
        'storage',
        'file_path',
        'chapter_id',
        'course_id',
        'instructor_id',
        'file_type',
        'duration',
        'is_preview',
        'downloadable',
        'description',
        'order',
        'is_published',
        'is_current'
    ];

    // Ghi đè lại logic publish mặc địnhdr
    public function replicateDraftAttributesTo(Model $target): void
    {
        parent::replicateDraftAttributesTo($target);
        $target->course_id = $target->getOriginal('course_id');
        $target->chapter_id = $target->getOriginal('chapter_id');
    }

    public function comments()
    {
        return $this->hasMany(LessonComment::class, 'lesson_id', 'id');
    }

}
