<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuggestByScoreRequest;
use App\Models\AdmissionCriterion;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class SuggestionController extends Controller
{
    public function suggestByScore(SuggestByScoreRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $studentScores = collect($validated['scores'])->keyBy('subject_name');

        // Lấy tất cả các tiêu chí xét tuyển phù hợp với phương thức và năm hiện tại
        $criteria = AdmissionCriterion::with(['major', 'admissionScores'])
            ->where('admission_method', $validated['admission_method'])
            // ->where('year', Carbon::now()->year) // Tạm thời vô hiệu hóa để debug
            ->get();

        $suggestedMajors = [];

        foreach ($criteria as $criterion) {
            $isEligible = true;
            
            // Kiểm tra từng điểm yêu cầu của tiêu chí
            foreach ($criterion->admissionScores as $requiredScore) {
                $subjectName = $requiredScore->subject_name;
                
                // Nếu thí sinh không có điểm môn được yêu cầu, hoặc điểm thấp hơn -> không đủ điều kiện
                if (!isset($studentScores[$subjectName]) || $studentScores[$subjectName]['score'] < $requiredScore->required_score) {
                    $isEligible = false;
                    break; 
                }
            }

            if ($isEligible) {
                // Thêm ngành học vào danh sách gợi ý (tránh trùng lặp)
                if (!isset($suggestedMajors[$criterion->major->id])) {
                    $suggestedMajors[$criterion->major->id] = [
                        'id' => $criterion->major->id,
                        'name' => $criterion->major->name,
                        'code' => $criterion->major->code,
                        'description' => $criterion->major->description,
                        'matched_criterion' => $criterion->name,
                    ];
                }
            }
        }

        return response()->json([
            'message' => 'Suggested majors based on your scores.',
            'data' => array_values($suggestedMajors)
        ]);
    }
}
