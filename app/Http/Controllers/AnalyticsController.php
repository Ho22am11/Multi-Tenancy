<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function AnalysisUser(Request $request)
{
    // معالجة فلاتر التاريخ
    $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : null;
    $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : null;

    return User::withCount([
        'assignedTasks' => function($query) use ($startDate, $endDate) {
            if ($startDate) {
                $query->where('created_at', '>=', $startDate);
            }
            if ($endDate) {
                $query->where('created_at', '<=', $endDate);
            }
        },
        'assignedTasks as pending_tasks' => function($query) use ($startDate, $endDate) {
            $query->where('state', 1);
            if ($startDate) $query->where('created_at', '>=', $startDate);
            if ($endDate) $query->where('created_at', '<=', $endDate);
        },
        'assignedTasks as in_progress_tasks' => function($query) use ($startDate, $endDate) {
            $query->where('state', 2);
            if ($startDate) $query->where('created_at', '>=', $startDate);
            if ($endDate) $query->where('created_at', '<=', $endDate);
        },
        'assignedTasks as completed_tasks' => function($query) use ($startDate, $endDate) {
            $query->where('state', 3);
            if ($startDate) $query->where('created_at', '>=', $startDate);
            if ($endDate) $query->where('created_at', '<=', $endDate);
        },
        'assignedTasks as low_tasks' => function($query) use ($startDate, $endDate) {
            $query->where('Priority', 1);
            if ($startDate) $query->where('created_at', '>=', $startDate);
            if ($endDate) $query->where('created_at', '<=', $endDate);
        },
        'assignedTasks as mid_tasks' => function($query) use ($startDate, $endDate) {
            $query->where('Priority', 2);
            if ($startDate) $query->where('created_at', '>=', $startDate);
            if ($endDate) $query->where('created_at', '<=', $endDate);
        },
        'assignedTasks as high_tasks' => function($query) use ($startDate, $endDate) {
            $query->where('Priority', 3);
            if ($startDate) $query->where('created_at', '>=', $startDate);
            if ($endDate) $query->where('created_at', '<=', $endDate);
        }
    ])
    ->with(['assignedTasks' => function($query) use ($startDate, $endDate) {
        $query->where('state', 3)
              ->whereNotNull('complete_at')
              ->when($startDate, function($q) use ($startDate) {
                  return $q->where('tasks.created_at', '>=', $startDate);
              })
              ->when($endDate, function($q) use ($endDate) {
                  return $q->where('tasks.created_at', '<=', $endDate);
              })
              ->select('tasks.id', 'tasks.created_at', 'tasks.complete_at', 'tasks.Priority');
    }])
    ->get()
    ->map(function($user) {
        $total = $user->assigned_tasks_count ?: 1;
        
        // حساب متوسط وقت الإكمال لكل أولوية
        $completionTimes = [
            'low' => [], 'mid' => [], 'high' => []
        ];
        
        foreach ($user->assignedTasks as $task) {
            $completionTime = Carbon::parse($task->created_at)
                ->diffInHours(Carbon::parse($task->complete_at));
                
            switch ($task->Priority) {
                case 1: $completionTimes['low'][] = $completionTime; break;
                case 2: $completionTimes['mid'][] = $completionTime; break;
                case 3: $completionTimes['high'][] = $completionTime; break;
            }
        }
        
        // حساب المتوسطات
        $avgCompletionTimes = array_map(function($times) {
            return !empty($times) ? round(array_sum($times) / count($times), 1) : 0;
        }, $completionTimes);

        return [
            'id' => $user->id,
            'name' => $user->name,
            'total' => $total,
            'tasks_by_state' => [
                'pending' => $user->pending_tasks,
                'in_progress' => $user->in_progress_tasks,
                'completed' => $user->completed_tasks,
            ],
            'tasks_by_priority' => [
                'low' => $user->low_tasks,
                'mid' => $user->mid_tasks,
                'high' => $user->high_tasks,
            ],
            'state_rates' => [
                'pending_rate' => round(($user->pending_tasks * 100) / $total, 1),
                'in_progress_rate' => round(($user->in_progress_tasks * 100) / $total, 1),
                'completed_rate' => round(($user->completed_tasks * 100) / $total, 1),
            ],
            'priority_rates' => [
                'low_rate' => round(($user->low_tasks * 100) / $total, 1),
                'mid_rate' => round(($user->mid_tasks * 100) / $total, 1),
                'high_rate' => round(($user->high_tasks * 100) / $total, 1)
            ],
            'completion_times' => [
                'low_priority_avg_hours' => $avgCompletionTimes['low'],
                'mid_priority_avg_hours' => $avgCompletionTimes['mid'],
                'high_priority_avg_hours' => $avgCompletionTimes['high'],
                'overall_avg_hours' => round(array_sum(array_map('array_sum', $completionTimes)) / 
                    max(count(array_merge(...array_values($completionTimes))), 1), 1)
            ]
        ];
    });
}



    
}
