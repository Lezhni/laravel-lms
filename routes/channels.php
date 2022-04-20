<?php

Broadcast::channel('student.{studentId}', function ($user, $studentId) {
    return Auth::user()->id === (int)$studentId;
});