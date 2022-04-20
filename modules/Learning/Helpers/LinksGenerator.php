<?php

namespace Modules\Learning\Helpers;

/**
 *
 */
final class LinksGenerator
{
    /**
     * @param int $courseId
     * @return string
     */
    public static function getCourseLink(int $courseId): string
    {
        return url("course/{$courseId}");
    }

    /**
     * @param int $courseId
     * @param int $lessonId
     * @return string
     */
    public static function getLessonLink(int $courseId, int $lessonId): string
    {
        return url("course/{$courseId}/lesson/{$lessonId}");
    }

    /**
     * @param int $courseId
     * @param int $lessonId
     * @return string
     */
    public static function getHomeworkLink(int $courseId, int $lessonId): string
    {
        return url("course/{$courseId}/lesson/{$lessonId}/schoolwork");
    }
}