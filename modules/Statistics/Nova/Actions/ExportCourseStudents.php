<?php

namespace Modules\Statistics\Nova\Actions;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Modules\Learning\Models\Course;
use Modules\Statistics\Jobs\RemoveTmpFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Throwable;

/**
 *
 */
class ExportCourseStudents extends Action
{
    /**
     * @var string
     */
    public $name = 'Экспортировать';

    /**
     * @var int
     */
    public static $chunkCount = 9999; // TODO: Change large chunk's count to native Laravel method for large data

    /**
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection $models
     * @return array
     */
    public function handle(ActionFields $fields, Collection $models): array
    {
        $colsNames = [
            'A' => 'ID',
            'B' => 'ФИО',
            'C' => 'Email',
            'D' => 'Дата начала обучения',
            'E' => 'Дата окончания обучения',
            'F' => 'Последняя активность'
        ];

        $courseId = $models->first()->pivot->course_id;
        $course = Course::find($courseId);
        $fileName = "Студенты курса «{$course->name}».xlsx";

        $data = $models
            ->reject(function (User $student) {
                return $student->is_admin;
            })
            ->map(function (User $student) {
                return [
                    $student->id,
                    $student->name,
                    $student->email,
                    $student->pivot->started_at ?? '-',
                    $student->pivot->finished_at ?? '-',
                    $student->last_activity_at ?? '-'
                ];
            })
            ->toArray();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        foreach ($colsNames as $letter => $name) {
            $sheet->setCellValue("{$letter}1", $name);
            $sheet->getColumnDimension($letter)->setAutoSize(true);
        }
        $sheet->fromArray($data, null, 'A2');

        try {
            $filePath = Storage::disk('tmp')->path($fileName);
            $writer = new Xlsx($spreadsheet);
            $writer->save($filePath);
        } catch (Throwable $e) {
            return Action::danger('Произошла ошибка при сохранении файла, попробуйте еще раз');
        }

        RemoveTmpFile::dispatch($fileName)->delay(Carbon::now()->addSeconds(5));

        $fileUrl = Storage::disk('tmp')->url($fileName);
        return Action::download($fileUrl, $fileName);
    }
}
