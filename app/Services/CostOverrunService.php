<?php

namespace App\Services;

use App\Models\Budget;
use App\Models\CostOverrunAlert;
use Illuminate\Support\Facades\Auth;

class CostOverrunService
{
    public const THRESHOLD_WARNING = 80;
    public const THRESHOLD_DANGER = 100;
    public const THRESHOLD_CRITICAL = 120;

    public function checkBudget(Budget $budget): ?CostOverrunAlert
    {
        if ($budget->budgeted_amount <= 0) {
            return null;
        }

        $pct = ($budget->actual_cost / $budget->budgeted_amount) * 100;
        $variance = $budget->budgeted_amount - $budget->actual_cost;

        if ($pct < self::THRESHOLD_WARNING) {
            return null;
        }

        $severity = $pct >= self::THRESHOLD_CRITICAL ? 'critical'
            : ($pct >= self::THRESHOLD_DANGER ? 'danger' : 'warning');

        $message = match ($severity) {
            'critical' => "Cost overrun critical: {$budget->cost_code} is at {$pct}% of budget (৳" . number_format($budget->actual_cost) . " vs ৳" . number_format($budget->budgeted_amount) . ")",
            'danger'   => "Budget exhausted: {$budget->cost_code} has reached {$pct}% of budget (৳" . number_format($budget->actual_cost) . " vs ৳" . number_format($budget->budgeted_amount) . ")",
            'warning'  => "Budget warning: {$budget->cost_code} is at {$pct}% of budget (৳" . number_format($budget->actual_cost) . " vs ৳" . number_format($budget->budgeted_amount) . ")",
        };

        $existing = CostOverrunAlert::where('budget_id', $budget->id)
            ->where('severity', $severity)
            ->where('status', 'open')
            ->first();

        if ($existing) {
            $existing->update([
                'actual_amount'      => $budget->actual_cost,
                'variance'           => $variance,
                'variance_percentage' => round($pct, 2),
                'message'            => $message,
            ]);
            return $existing;
        }

        return CostOverrunAlert::create([
            'project_id'          => $budget->project_id,
            'budget_id'           => $budget->id,
            'cost_code'           => $budget->cost_code,
            'budgeted_amount'     => $budget->budgeted_amount,
            'actual_amount'       => $budget->actual_cost,
            'variance'            => $variance,
            'variance_percentage' => round($pct, 2),
            'severity'            => $severity,
            'message'             => $message,
            'status'              => 'open',
            'created_by'          => Auth::id(),
        ]);
    }

    public function checkAllBudgets(): array
    {
        $counts = ['created' => 0, 'updated' => 0];
        $budgets = Budget::where('budgeted_amount', '>', 0)->get();

        foreach ($budgets as $budget) {
            $pct = ($budget->actual_cost / $budget->budgeted_amount) * 100;
            if ($pct < self::THRESHOLD_WARNING) {
                continue;
            }

            $existing = CostOverrunAlert::where('budget_id', $budget->id)
                ->whereIn('severity', $pct >= self::THRESHOLD_CRITICAL ? ['warning', 'danger', 'critical']
                    : ($pct >= self::THRESHOLD_DANGER ? ['warning', 'danger'] : ['warning']))
                ->where('status', 'open')
                ->first();

            if (!$existing) {
                $this->checkBudget($budget);
                $counts['created']++;
            }
        }

        return $counts;
    }

    public function acknowledge(int $alertId, ?string $notes = null): bool
    {
        return CostOverrunAlert::where('id', $alertId)->where('status', 'open')->update([
            'status'           => 'acknowledged',
            'acknowledged_at'  => now(),
            'acknowledged_by'  => Auth::id(),
            'resolution_notes' => $notes,
        ]) > 0;
    }

    public function resolve(int $alertId, string $notes): bool
    {
        return CostOverrunAlert::where('id', $alertId)->whereIn('status', ['open', 'acknowledged'])->update([
            'status'           => 'resolved',
            'acknowledged_at'  => now(),
            'acknowledged_by'  => Auth::id(),
            'resolution_notes' => $notes,
        ]) > 0;
    }
}
