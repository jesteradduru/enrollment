<?php

namespace App\Filament\Faculty\Pages;

use App\Models\User;
use App\Models\Level;
use App\Models\SchoolYear;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.reports';

    protected static ?int $navigationSort  = 100;

    public $faculty_id;
    public $grade_id;
    public $school_year_id;
    public $enrollees = [];
    public $showReport = false;

    public function getFacultiesProperty()
    {
        return [];
    }

    public function getGradesProperty()
    {
        return DB::table('enrollments')->join('classrooms', 'enrollments.classroom_id', '=', 'classrooms.id')
            ->join('faculty_classroom', 'faculty_classroom.classroom_id', '=', 'classrooms.id')
            ->leftJoin('levels', 'classrooms.level_id', '=', 'levels.id')
            ->where('faculty_classroom.faculty_id', auth()->user()->id)
            ->select('levels.*')
            ->get()
            ->pluck('level', 'id');
    }

    public function getSchoolYearsProperty()
    {
        return SchoolYear::orderByDesc('name')->pluck('name', 'id');
    }

    public function generateReport()
    {
        $this->showReport = true;
        $query = DB::table('enrollments')
            ->join('students', 'enrollments.student_id', '=', 'students.id')
            ->join('classrooms', 'enrollments.classroom_id', '=', 'classrooms.id')
            ->join('faculty_classroom', 'faculty_classroom.classroom_id', '=', 'classrooms.id')
            ->leftJoin('levels', 'classrooms.level_id', '=', 'levels.id')
            ->where('faculty_classroom.faculty_id', auth()->user()->id)
            ->select(
                'students.*',
                'levels.level',
                'levels.grade',
                'levels.type as level_type',
                'students.type',
                'students.school_id',
                'students.gender',
                'students.date_of_birth',
                'enrollments.created_at as enrolled_at',
                'classrooms.name as classroom_name',
                'classrooms.level_id',
                'classrooms.id as classroom_id'
            );

        if ($this->school_year_id) {
            $query->where('enrollments.school_year_id', $this->school_year_id);
        }
        if ($this->faculty_id) {
            $query->whereExists(function($sub) {
                $sub->select(DB::raw(1))
                    ->from('faculty_classroom')
                    ->whereColumn('faculty_classroom.classroom_id', 'classrooms.id')
                    ->where('faculty_classroom.faculty_id', $this->faculty_id);
            });
        }
        if ($this->grade_id) {
            $query->where('classrooms.level_id', $this->grade_id);
        }

        $this->enrollees = $query->get();
    }

    public function getAge($date){
        return Carbon::parse($date)->age;
    }
    public function getDateOfBirth($date){
        return Carbon::parse($date)->format('m-d-Y');
    }

    public function exportPdf()
    {
        $schoolYear = $this->schoolYears[$this->school_year_id] ?? '';
        $enrollees = $this->enrollees;
        $pdf = Pdf::loadView('reports.pdf', [
            'enrollees' => $enrollees,
            'schoolYear' => $schoolYear,
        ])->setPaper('A4', 'portrait');

        return Response::streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'enrollees_report.pdf');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Define your form schema here
            ]);
    }
}
