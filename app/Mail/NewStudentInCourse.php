<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Course;

class NewStudentInCourse extends Mailable
{
    use Queueable, SerializesModels;

    /**
	 * @var Course
	 */
	private $course;
	private $student_name;

	/**
	 * Create a new message instance.
	 *
	 * @param Course $course
	 * @param $student_name
	 */
    public function __construct(Course $course, $student_name)
    {
	    $this->course = $course;
	    $this->student_name = $student_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
			->subject(__("Nuevo estudiante inscrito en tu curso"))
	        ->markdown('emails.new_student_in_course') //template que utilitzara
	        ->with('course', $this->course)
	        ->with('student', $this->student_name);
    }
}
