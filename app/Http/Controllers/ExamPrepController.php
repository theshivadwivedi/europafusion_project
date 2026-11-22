<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Faq;
use Illuminate\View\View;

class ExamPrepController extends Controller
{
    public function index(): View
    {
        $exams = Exam::all()->groupBy('category');

        return view('pages.exam-prep', [
            'languageTests' => $exams->get('language', collect()),
            'standardizedTests' => $exams->get('standardized', collect()),
            'faqs' => Faq::group('exam-prep'),
        ]);
    }
}
