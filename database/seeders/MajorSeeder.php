<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Major;
use App\Models\AdmissionCriterion;
use App\Models\AdmissionScore;
use Illuminate\Support\Facades\DB;

class MajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Major 1: Công nghệ thông tin
            $itMajor = Major::create([
                'name' => 'Công nghệ thông tin',
                'code' => '7480201',
                'description' => 'Ngành học về phát triển phần mềm, hệ thống thông tin, và mạng máy tính.'
            ]);

            // Criterion 1.1: Xét điểm thi THPT khối A00
            $itCriterion1 = AdmissionCriterion::create([
                'major_id' => $itMajor->id,
                'name' => 'Xét tuyển khối A00 - Điểm thi THPT 2024',
                'admission_method' => 'exam_score',
                'year' => 2024,
            ]);
            AdmissionScore::create(['admission_criterion_id' => $itCriterion1->id, 'subject_name' => 'Toán', 'required_score' => 8.0]);
            AdmissionScore::create(['admission_criterion_id' => $itCriterion1->id, 'subject_name' => 'Lý', 'required_score' => 7.5]);
            AdmissionScore::create(['admission_criterion_id' => $itCriterion1->id, 'subject_name' => 'Hóa', 'required_score' => 7.5]);

            // Criterion 1.2: Xét học bạ 3 môn
            $itCriterion2 = AdmissionCriterion::create([
                'major_id' => $itMajor->id,
                'name' => 'Xét tuyển học bạ 3 môn lớp 12',
                'admission_method' => 'transcript_score',
                'year' => 2024,
            ]);
            AdmissionScore::create(['admission_criterion_id' => $itCriterion2->id, 'subject_name' => 'Toán', 'required_score' => 8.5]);
            AdmissionScore::create(['admission_criterion_id' => $itCriterion2->id, 'subject_name' => 'Lý', 'required_score' => 8.0]);
            AdmissionScore::create(['admission_criterion_id' => $itCriterion2->id, 'subject_name' => 'Anh', 'required_score' => 8.0]);

            // Major 2: Quản trị kinh doanh
            $baMajor = Major::create([
                'name' => 'Quản trị kinh doanh',
                'code' => '7340101',
                'description' => 'Ngành học về quản lý hoạt động kinh doanh, marketing, và nhân sự.'
            ]);

            // Criterion 2.1: Xét điểm thi THPT khối A01
            $baCriterion1 = AdmissionCriterion::create([
                'major_id' => $baMajor->id,
                'name' => 'Xét tuyển khối A01 - Điểm thi THPT 2024',
                'admission_method' => 'exam_score',
                'year' => 2024,
            ]);
            AdmissionScore::create(['admission_criterion_id' => $baCriterion1->id, 'subject_name' => 'Toán', 'required_score' => 7.5]);
            AdmissionScore::create(['admission_criterion_id' => $baCriterion1->id, 'subject_name' => 'Lý', 'required_score' => 7.0]);
            AdmissionScore::create(['admission_criterion_id' => $baCriterion1->id, 'subject_name' => 'Anh', 'required_score' => 8.0]);
        });
    }
}
