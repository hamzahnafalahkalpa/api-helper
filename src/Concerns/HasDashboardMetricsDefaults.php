<?php

namespace Hanafalah\ApiHelper\Concerns;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Shared trait for dashboard metrics defaults.
 * Used by both Gateway (read-only) and Backbone (read-write) services.
 *
 * Requirements:
 * - Implementing class must have a `$client` property with Elasticsearch client
 * - Implementing class must have a `$indexPrefix` property (default: 'dashboard-metrics')
 */
trait HasDashboardMetricsDefaults
{
    public const PERIOD_DAILY = 'daily';
    public const PERIOD_WEEKLY = 'weekly';
    public const PERIOD_MONTHLY = 'monthly';
    public const PERIOD_YEARLY = 'yearly';

    /**
     * Get all available period types.
     */
    public function getPeriodTypes(): array
    {
        return [self::PERIOD_DAILY, self::PERIOD_WEEKLY, self::PERIOD_MONTHLY, self::PERIOD_YEARLY];
    }

    /**
     * Get the number of periods to show for each period type.
     */
    protected function getPeriodCount(string $periodType): int
    {
        return match ($periodType) {
            self::PERIOD_DAILY => 7,    // 7 days
            self::PERIOD_WEEKLY => 4,   // 4 weeks
            self::PERIOD_MONTHLY => 12, // 12 months
            self::PERIOD_YEARLY => 5,   // 5 years
            default => 7
        };
    }

    /**
     * Get change label based on period type.
     */
    protected function getChangeLabel(string $periodType): string
    {
        return match ($periodType) {
            self::PERIOD_DAILY => 'Dari kemarin',
            self::PERIOD_WEEKLY => 'Dari minggu lalu',
            self::PERIOD_MONTHLY => 'Dari bulan lalu',
            self::PERIOD_YEARLY => 'Dari tahun lalu',
            default => 'Dari kemarin'
        };
    }

    /**
     * Get period label for display.
     */
    protected function getPeriodLabel(string $periodType, Carbon $timestamp): string
    {
        return match ($periodType) {
            self::PERIOD_DAILY => $timestamp->format('d M Y'),
            self::PERIOD_WEEKLY => 'Minggu ' . $timestamp->format('W, Y'),
            self::PERIOD_MONTHLY => $timestamp->format('F Y'),
            self::PERIOD_YEARLY => $timestamp->format('Y'),
            default => $timestamp->toDateString()
        };
    }

    /**
     * Get default motivational stats structure.
     */
    protected function getDefaultMotivationalStats(): array
    {
        return [
            'current' => 0,
            'target' => 0,
            'percentage' => 0,
            'remaining' => 0,
            'growth' => 0,
            'growth_percentage' => 0,
        ];
    }

    /**
     * Get default statistics array with frontend presentation structure.
     */
    protected function getDefaultStatistics(string $periodType, ?array $data = []): array
    {
        $changeLabel = $this->getChangeLabel($periodType);
        $response = [
            [
                'id' => 'patients',
                'label' => 'Jumlah Pasien',
                'count' => 0,
                'change' => 0,
                'change_type' => 'increase',
                'percentage_change' => 0,
                'change_label' => $changeLabel,
                'icon' => 'mdi:account-group',
                'color' => 'blue',
                'gradient' => 'from-blue-500 to-cyan-400',
                'bg_light' => 'bg-blue-50',
                'text_color' => 'text-blue-600',
                'border_color' => 'border-blue-200'
            ],
            [
                'id' => 'new-patients',
                'label' => 'Pasien Baru',
                'count' => 0,
                'change' => 0,
                'change_type' => 'increase',
                'percentage_change' => 0,
                'change_label' => $changeLabel,
                'icon' => 'mdi:account-plus',
                'color' => 'purple',
                'gradient' => 'from-purple-500 to-pink-400',
                'bg_light' => 'bg-purple-50',
                'text_color' => 'text-purple-600',
                'border_color' => 'border-purple-200'
            ],
            [
                'id' => 'revenue',
                'label' => 'Omzet',
                'count' => 0,
                'change' => 0,
                'change_type' => 'increase',
                'percentage_change' => 0,
                'change_label' => $changeLabel,
                'icon' => 'mdi:cash-multiple',
                'color' => 'emerald',
                'gradient' => 'from-emerald-500 to-teal-400',
                'bg_light' => 'bg-emerald-50',
                'text_color' => 'text-emerald-600',
                'border_color' => 'border-emerald-200',
                'is_currency' => true
            ],
            [
                'id' => 'treatment',
                'label' => 'Tindakan Dipesankan',
                'count' => 0,
                'change' => 0,
                'change_type' => 'increase',
                'percentage_change' => 0,
                'change_label' => $changeLabel,
                'icon' => 'mdi:clipboard-list',
                'color' => 'orange',
                'gradient' => 'from-orange-500 to-amber-400',
                'bg_light' => 'bg-orange-50',
                'text_color' => 'text-orange-600',
                'border_color' => 'border-orange-200'
            ]
        ];

        if (count($data) > 0) {
            foreach ($response as &$resp) {
                $id = Str::snake($resp['id']);
                if (isset($data[$id])) {
                    foreach ($data[$id] as $key => $data_item) {
                        $resp[$key] = $data_item;
                    }
                }
            }
        }

        return $response;
    }

    /**
     * Get default pending items structure.
     * Override this method in implementing classes for custom structure.
     */
    protected function getDefaultPendingItems(string $periodType, ?array $data = []): array
    {
        $changeLabel = $this->getChangeLabel($periodType);
        $response = [
            [
                'id' => 'unsigned-visits',
                'label' => 'Unsigned visits',
                'change_label' => $changeLabel,
                'count' => 0,
                'icon' => 'mdi:file-document-edit-outline',
                'color' => 'text-orange-600',
                'link' => '/patient-emr/visit-registration?is_unsigned_visits=1'
            ],
            [
                'id' => 'unsynced-patients',
                'label' => 'Belum tersinkronisasi satu sehat',
                'change_label' => $changeLabel,
                'count' => 0,
                'icon' => 'mdi:sync-alert',
                'color' => 'text-red-600',
                'link' => '/satu-sehat/dashboard'
            ],
            [
                'id' => 'incomplete-diagnosis',
                'label' => 'Tanpa ICD',
                'change_label' => $changeLabel,
                'count' => 0,
                'icon' => 'mdi:alert-circle',
                'color' => 'text-amber-600',
                'link' => '/patient-emr/incomplete-diagnosis'
            ]
        ];

        if (count($data) > 0) {
            foreach ($response as &$resp) {
                $id = Str::snake($resp['id']);
                if (isset($data[$id])) {
                    foreach ($data[$id] as $key => $data_item) {
                        $resp[$key] = $data_item;
                    }
                }
            }
        }

        return $response;
    }

    /**
     * Get default cashier structure.
     * Override this method in implementing classes for custom structure.
     */
    protected function getDefaultCashier(string $periodType, ?array $data = []): array
    {
        $changeLabel = $this->getChangeLabel($periodType);
        $response = [
            [
                'id' => 'revenue',
                'label' => 'Omzet',
                'count' => 0,
                'change' => 0,
                'changeType' => 'increase',
                'percentage_change' => 0,
                'changeLabel' => $changeLabel,
                'icon' => 'mdi:cash-multiple',
                'color' => 'emerald',
                'gradient' => 'from-emerald-500 to-teal-400',
                'bgLight' => 'bg-emerald-50',
                'textColor' => 'text-emerald-600',
                'borderColor' => 'border-emerald-200',
                'isCurrency' => true,
            ],
            [
                'id' => 'unpaid',
                'label' => 'Jumlah Belum Dibayar',
                'count' => 0,
                'change' => 0,
                'changeType' => 'increase',
                'percentage_change' => 0,
                'changeLabel' => $changeLabel,
                'icon' => 'mdi:alert-circle',
                'color' => 'red',
                'gradient' => 'from-red-500 to-rose-400',
                'bgLight' => 'bg-red-50',
                'textColor' => 'text-red-600',
                'borderColor' => 'border-red-200',
                'isCurrency' => true,
            ],
            [
                'id' => 'total-transactions',
                'label' => 'Jumlah Transaksi',
                'count' => 0,
                'change' => 0,
                'changeType' => 'increase',
                'percentage_change' => 0,
                'changeLabel' => $changeLabel,
                'icon' => 'mdi:receipt-text',
                'color' => 'blue',
                'gradient' => 'from-blue-500 to-cyan-400',
                'bgLight' => 'bg-blue-50',
                'textColor' => 'text-blue-600',
                'borderColor' => 'border-blue-200',
            ],
            [
                'id' => 'pending',
                'label' => 'Jumlah Pending',
                'count' => 0,
                'change' => 0,
                'changeType' => 'increase',
                'percentage_change' => 0,
                'changeLabel' => $changeLabel,
                'icon' => 'mdi:clock-alert',
                'color' => 'orange',
                'gradient' => 'from-orange-500 to-amber-400',
                'bgLight' => 'bg-orange-50',
                'textColor' => 'text-orange-600',
                'borderColor' => 'border-orange-200',
            ]
        ];

        if (count($data) > 0) {
            foreach ($response as &$resp) {
                $id = Str::snake($resp['id']);
                if (isset($data[$id])) {
                    foreach ($data[$id] as $key => $data_item) {
                        $resp[$key] = $data_item;
                    }
                }
            }
        }

        return $response;
    }

    /**
     * Get default billing structure.
     * Override this method in implementing classes for custom structure.
     */
    protected function getDefaultBilling(string $periodType, ?array $data = []): array
    {
        $changeLabel = $this->getChangeLabel($periodType);
        $response = [
            [
                'id' => 'total-billing',
                'label' => 'Total Billing',
                'count' => 0,
                'change' => 0,
                'changeType' => 'increase',
                'percentage_change' => 0,
                'changeLabel' => $changeLabel,
                'icon' => 'mdi:receipt-text',
                'color' => 'blue',
                'gradient' => 'from-blue-500 to-cyan-400',
                'bgLight' => 'bg-blue-50',
                'textColor' => 'text-blue-600',
                'borderColor' => 'border-blue-200'
            ],
            [
                'id' => 'unpaid-billing',
                'label' => 'Billing Belum Lunas',
                'count' => 0,
                'change' => 0,
                'changeType' => 'increase',
                'changeLabel' => $changeLabel,
                'icon' => 'mdi:clock-alert',
                'color' => 'orange',
                'gradient' => 'from-orange-500 to-amber-400',
                'bgLight' => 'bg-orange-50',
                'textColor' => 'text-orange-600',
                'borderColor' => 'border-orange-200'
            ],
            [
                'id' => 'paid-billing',
                'label' => 'Billing Lunas',
                'count' => 0,
                'change' => 0,
                'changeType' => 'increase',
                'changeLabel' => $changeLabel,
                'icon' => 'mdi:check-circle',
                'color' => 'green',
                'gradient' => 'from-green-500 to-emerald-400',
                'bgLight' => 'bg-green-50',
                'textColor' => 'text-green-600',
                'borderColor' => 'border-green-200'
            ],
            [
                'id' => 'total-revenue',
                'label' => 'Total Pendapatan',
                'count' => 0,
                'change' => 0,
                'changeType' => 'increase',
                'changeLabel' => $changeLabel,
                'icon' => 'mdi:cash-multiple',
                'color' => 'emerald',
                'gradient' => 'from-emerald-500 to-teal-400',
                'bgLight' => 'bg-emerald-50',
                'textColor' => 'text-emerald-600',
                'borderColor' => 'border-emerald-200',
                'isCurrency' => true
            ]
        ];

        if (count($data) > 0) {
            foreach ($response as &$resp) {
                $id = Str::snake($resp['id']);
                if (isset($data[$id])) {
                    foreach ($data[$id] as $key => $data_item) {
                        $resp[$key] = $data_item;
                    }
                }
            }
        }

        return $response;
    }

    /**
     * Get default workspace integrations structure.
     * Override this method in implementing classes for custom structure.
     */
    protected function getDefaultWorkspaceIntegrations(string $periodType): array
    {
        $changeLabel = $this->getChangeLabel($periodType);

        return [
            'flag' => 'satu-sehat',
            'label' => 'Satu Sehat',
            'progress' => 0,
            'last_updated_at' => now()->format('Y-m-d H:i:s'),
            'from' => 0,
            'to' => 0,
            'general' => [
                'ihs_number' => null
            ],
            'syncs' => [
                [
                    'flag' => 'encounter',
                    'label' => 'Kunjungan',
                    'progress' => 0,
                    'last_updated_at' => now()->format('Y-m-d H:i:s'),
                    'from' => 0,
                    'to' => 0,
                    'success_count' => 0,
                    'failed_count' => 0,
                    'change_label' => $changeLabel
                ],
                [
                    'flag' => 'dispense',
                    'label' => 'Resep',
                    'progress' => 0,
                    'last_updated_at' => now()->format('Y-m-d H:i:s'),
                    'from' => 0,
                    'to' => 0,
                    'success_count' => 0,
                    'failed_count' => 0,
                    'change_label' => $changeLabel
                ],
                [
                    'flag' => 'condition',
                    'label' => 'Diagnosa',
                    'progress' => 0,
                    'last_updated_at' => now()->format('Y-m-d H:i:s'),
                    'from' => 0,
                    'to' => 0,
                    'success_count' => 0,
                    'failed_count' => 0,
                    'change_label' => $changeLabel
                ],
                [
                    'flag' => 'patient',
                    'label' => 'Pasien',
                    'progress' => 0,
                    'last_updated_at' => now()->format('Y-m-d H:i:s'),
                    'from' => 0,
                    'to' => 0,
                    'success_count' => 0,
                    'failed_count' => 0,
                    'change_label' => $changeLabel
                ],
                [
                    'flag' => 'location',
                    'label' => 'Lokasi/Ruangan',
                    'progress' => 0,
                    'last_updated_at' => now()->format('Y-m-d H:i:s'),
                    'from' => 0,
                    'to' => 0,
                    'success_count' => 0,
                    'failed_count' => 0,
                    'change_label' => $changeLabel
                ],
                [
                    'flag' => 'practitioner',
                    'label' => 'Tenaga Kesehatan',
                    'progress' => 0,
                    'last_updated_at' => now()->format('Y-m-d H:i:s'),
                    'from' => 0,
                    'to' => 0,
                    'success_count' => 0,
                    'failed_count' => 0,
                    'change_label' => $changeLabel
                ]
            ],
            'logs' => []
        ];
    }

    /**
     * Get default trends structure.
     */
    protected function getDefaultTrends(string $periodType, Carbon $timestamp): array
    {
        return [
            'services' => [],
            'dataset' => [
                'source' => [
                    $this->getTrendLabels($periodType, $timestamp)
                ]
            ],
            'title' => 'Tren Kunjungan per Poliklinik',
            'subtitle' => $this->getTrendSubtitle($periodType),
            'chart_type' => 'line',
            'series_layout' => 'row',
            'period_type' => $periodType
        ];
    }

    /**
     * Get x-axis labels for the trend chart.
     */
    protected function getTrendLabels(string $periodType, Carbon $timestamp): array
    {
        $labels = ['Kunjungan']; // First element is the header
        $count = $this->getPeriodCount($periodType);
        $now = Carbon::now();

        for ($i = $count - 1; $i >= 0; $i--) {
            $periodTimestamp = match ($periodType) {
                self::PERIOD_DAILY => $now->copy()->subDays($i),
                self::PERIOD_WEEKLY => $now->copy()->subWeeks($i),
                self::PERIOD_MONTHLY => $now->copy()->subMonths($i),
                self::PERIOD_YEARLY => $now->copy()->subYears($i),
                default => $now->copy()->subDays($i)
            };

            $labels[] = $this->getTrendPeriodLabel($periodType, $periodTimestamp);
        }

        return $labels;
    }

    /**
     * Get label for a specific trend period.
     */
    protected function getTrendPeriodLabel(string $periodType, Carbon $timestamp): string
    {
        return match ($periodType) {
            self::PERIOD_DAILY => $timestamp->format('d M'),
            self::PERIOD_WEEKLY => 'W' . $timestamp->format('W'),
            self::PERIOD_MONTHLY => $timestamp->format('M Y'),
            self::PERIOD_YEARLY => $timestamp->format('Y'),
            default => $timestamp->format('d M')
        };
    }

    /**
     * Get subtitle for trend chart.
     */
    protected function getTrendSubtitle(string $periodType): string
    {
        return match ($periodType) {
            self::PERIOD_DAILY => 'Berdasarkan 7 hari terakhir',
            self::PERIOD_WEEKLY => 'Berdasarkan 4 minggu terakhir',
            self::PERIOD_MONTHLY => 'Berdasarkan 12 bulan terakhir',
            self::PERIOD_YEARLY => 'Berdasarkan 5 tahun terakhir',
            default => ''
        };
    }

    /**
     * Get Elasticsearch index name.
     */
    protected function getIndexName(string $periodType): string
    {
        $prefix = config('elasticsearch.prefix', 'development');
        $separator = config('elasticsearch.separator', '.');
        return $prefix . $separator . $this->indexPrefix . '-' . $periodType;
    }

    /**
     * Generate document ID for a period.
     */
    protected function generateDocumentId(string $periodType, int $tenantId, mixed $workspaceId, Carbon $timestamp): string
    {
        $periodKey = match ($periodType) {
            self::PERIOD_DAILY => $timestamp->format('Y-m-d'),
            self::PERIOD_WEEKLY => $timestamp->format('Y') . '-W' . $timestamp->format('W'),
            self::PERIOD_MONTHLY => $timestamp->format('Y-m'),
            self::PERIOD_YEARLY => $timestamp->format('Y'),
            default => $timestamp->format('Y-m-d')
        };
        return "{$periodType}_{$tenantId}_{$workspaceId}_{$periodKey}";
    }

    /**
     * Get default document structure for dashboard metrics.
     */
    protected function getDefaultDocument(string $periodType, int $tenantId, mixed $workspaceId, Carbon $timestamp): array
    {
        return [
            'tenant_id' => $tenantId,
            'workspace_id' => $workspaceId,
            'period_type' => $periodType,
            'timestamp' => $timestamp->toIso8601String(),
            'date' => $timestamp->toDateString(),
            'year' => $timestamp->year,
            'month' => $timestamp->month,
            'week' => (int) $timestamp->format('W'),
            'day' => $timestamp->day,
            'statistics' => $this->getDefaultStatistics($periodType),
            'motivational_stats' => $this->getDefaultMotivationalStats(),
            'pending_items' => $this->getDefaultPendingItems($periodType),
            'cashier' => $this->getDefaultCashier($periodType),
            'billing' => $this->getDefaultBilling($periodType),
            'queue_services' => [],
            'diagnosis_treatment' => [],
            'workspace_integrations' => $this->getDefaultWorkspaceIntegrations($periodType),
            'trends' => $this->getDefaultTrends($periodType, $timestamp),
            'aggregation_period' => [
                'start_date' => $timestamp->toDateString(),
                'end_date' => $timestamp->toDateString(),
                'label' => $this->getPeriodLabel($periodType, $timestamp)
            ],
            'metadata' => [
                'created_at' => now()->toIso8601String(),
                'updated_at' => now()->toIso8601String(),
                'created_by' => 'system',
                'version' => '1.0'
            ]
        ];
    }

    /**
     * Format ES response to standard dashboard response structure.
     */
    protected function formatDashboardResponse(array $response, array $params): array
    {
        $now = Carbon::now();
        $periodType = $params['search_type'] ?? self::PERIOD_DAILY;

        // No data found - return defaults
        if (empty($response['hits']['hits'])) {
            return $this->getDefaultDashboardResponse($periodType, $now, $params);
        }

        $hit = $response['hits']['hits'][0]['_source'];

        // Return ES data with fallbacks for missing fields
        return [
            'motivational_stats' => $hit['motivational_stats'] ?? $this->getDefaultMotivationalStats(),
            'statistics' => $hit['statistics'] ?? $this->getDefaultStatistics($periodType),
            'pending_items' => $hit['pending_items'] ?? $this->getDefaultPendingItems($periodType),
            'cashier' => $hit['cashier'] ?? $this->getDefaultCashier($periodType),
            'billing' => $hit['billing'] ?? $this->getDefaultBilling($periodType),
            'queue_services' => $hit['queue_services'] ?? [],
            'diagnosis_treatment' => $hit['diagnosis_treatment'] ?? [],
            'workspace_integrations' => $hit['workspace_integrations'] ?? $this->getDefaultWorkspaceIntegrations($periodType),
            'trends' => $hit['trends'] ?? $this->getDefaultTrends($periodType, $now),
            'meta' => [
                'period_type' => $hit['period_type'] ?? $periodType,
                'timestamp' => $hit['timestamp'] ?? $now->toIso8601String(),
                'date' => $hit['date'] ?? $now->format('Y-m-d'),
                'year' => $hit['year'] ?? $now->year,
                'month' => $hit['month'] ?? $now->month,
                'week' => $hit['week'] ?? (int) $now->format('W'),
                'day' => $hit['day'] ?? $now->day,
                'data_source' => 'elasticsearch',
                'aggregation_period' => $hit['aggregation_period'] ?? null,
            ],
        ];
    }

    /**
     * Get default dashboard response when no data exists.
     */
    protected function getDefaultDashboardResponse(string $periodType, Carbon $now, array $params = []): array
    {
        return [
            'motivational_stats' => $this->getDefaultMotivationalStats(),
            'statistics' => $this->getDefaultStatistics($periodType),
            'pending_items' => $this->getDefaultPendingItems($periodType),
            'cashier' => $this->getDefaultCashier($periodType),
            'billing' => $this->getDefaultBilling($periodType),
            'queue_services' => [],
            'diagnosis_treatment' => [],
            'workspace_integrations' => $this->getDefaultWorkspaceIntegrations($periodType),
            'trends' => $this->getDefaultTrends($periodType, $now),
            'meta' => [
                'period_type' => $periodType,
                'message' => 'No data available for the specified period',
                'timestamp' => $now->toIso8601String(),
                'date' => $now->format('Y-m-d'),
                'year' => $now->year,
                'month' => $now->month,
                'week' => (int) $now->format('W'),
                'day' => $now->day,
                'data_source' => 'elasticsearch',
            ],
        ];
    }

    /**
     * Get timestamp for query based on params.
     */
    protected function getTimestampFromParams(array $params): Carbon
    {
        $periodType = $params['search_type'] ?? self::PERIOD_DAILY;
        $now = Carbon::now();

        return match ($periodType) {
            self::PERIOD_DAILY => !empty($params['search_date'])
                ? Carbon::parse($params['search_date'])
                : $now,
            self::PERIOD_WEEKLY => !empty($params['search_year']) && !empty($params['search_week'])
                ? Carbon::now()->setISODate((int) $params['search_year'], (int) $params['search_week'])
                : $now,
            self::PERIOD_MONTHLY => !empty($params['search_year']) && !empty($params['search_month'])
                ? Carbon::createFromDate((int) $params['search_year'], (int) $params['search_month'], 1)
                : $now,
            self::PERIOD_YEARLY => !empty($params['search_year'])
                ? Carbon::createFromDate((int) $params['search_year'], 1, 1)
                : $now,
            default => $now
        };
    }

    // =========================================================================
    // Elasticsearch Operations
    // =========================================================================

    /**
     * Ensure Elasticsearch index exists, create if not.
     * Uses the standard dashboard metrics mappings.
     */
    protected function ensureIndexExists(string $periodType): bool
    {
        try {
            $indexName = $this->getIndexName($periodType);

            // Check if index exists - handle different client response types
            $exists = $this->client->indices()->exists(['index' => $indexName]);
            $indexExists = is_bool($exists) ? $exists : (method_exists($exists, 'asBool') ? $exists->asBool() : (bool) $exists);

            if (!$indexExists) {
                $this->client->indices()->create([
                    'index' => $indexName,
                    'body' => [
                        'settings' => $this->getIndexSettings(),
                        'mappings' => ['properties' => $this->getIndexMappings()]
                    ]
                ]);

                Log::channel('elasticsearch')->info('Created dashboard metrics index', ['index' => $indexName]);
            }

            return true;
        } catch (\Throwable $e) {
            Log::channel('elasticsearch')->error('Failed to ensure index exists', [
                'error' => $e->getMessage(),
                'period_type' => $periodType
            ]);
            return true; // Return true to allow operation to continue
        }
    }

    /**
     * Get Elasticsearch index settings.
     */
    protected function getIndexSettings(): array
    {
        return [
            'number_of_shards' => 1,
            'number_of_replicas' => 0
        ];
    }

    /**
     * Get Elasticsearch index mappings for dashboard metrics.
     */
    protected function getIndexMappings(): array
    {
        return [
            'tenant_id' => ['type' => 'integer'],
            'workspace_id' => ['type' => 'integer'],
            'period_type' => ['type' => 'keyword'],
            'timestamp' => ['type' => 'date'],
            'date' => ['type' => 'date', 'format' => 'yyyy-MM-dd'],
            'year' => ['type' => 'integer'],
            'month' => ['type' => 'integer'],
            'week' => ['type' => 'integer'],
            'day' => ['type' => 'integer'],
            'statistics' => ['type' => 'nested'],
            'motivational_stats' => ['type' => 'object'],
            'pending_items' => ['type' => 'nested'],
            'cashier' => ['type' => 'nested'],
            'billing' => ['type' => 'nested'],
            'queue_services' => ['type' => 'nested'],
            'diagnosis_treatment' => ['type' => 'nested'],
            'workspace_integrations' => ['type' => 'object'],
            'trends' => ['type' => 'object'],
            'aggregation_period' => ['type' => 'object'],
            'metadata' => ['type' => 'object']
        ];
    }

    /**
     * Create default document in Elasticsearch if it doesn't exist.
     * Uses op_type 'create' to prevent overwriting existing documents.
     */
    protected function createDefaultDocument(string $periodType, int $tenantId, mixed $workspaceId, Carbon $timestamp): array
    {
        try {
            $documentId = $this->generateDocumentId($periodType, $tenantId, $workspaceId, $timestamp);
            $document = $this->getDefaultDocument($periodType, $tenantId, $workspaceId, $timestamp);

            $response = $this->client->index([
                'index' => $this->getIndexName($periodType),
                'id' => $documentId,
                'body' => $document,
                'op_type' => 'create' // Only create if doesn't exist
            ]);

            Log::channel('elasticsearch')->info('Created default dashboard metrics document', [
                'index' => $this->getIndexName($periodType),
                'document_id' => $documentId,
                'tenant_id' => $tenantId,
                'workspace_id' => $workspaceId,
                'period_type' => $periodType
            ]);

            return ['success' => true, 'id' => $response['_id'] ?? null];

        } catch (\Throwable $e) {
            // Document already exists (conflict) - that's fine
            if (str_contains($e->getMessage(), 'version_conflict_engine_exception') ||
                str_contains($e->getMessage(), 'document already exists')) {
                return ['success' => true, 'already_exists' => true];
            }

            Log::channel('elasticsearch')->warning('Failed to create default dashboard document', [
                'error' => $e->getMessage(),
                'period_type' => $periodType,
                'tenant_id' => $tenantId
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Store/update document in Elasticsearch.
     * This method will create or update the document (upsert behavior).
     */
    protected function storeDocument(array $data, string $periodType, int $tenantId, mixed $workspaceId, Carbon $timestamp): array
    {
        try {
            $this->ensureIndexExists($periodType);

            // Ensure workspace_integrations is never null
            if (!isset($data['workspace_integrations']) || !is_array($data['workspace_integrations'])) {
                $data['workspace_integrations'] = $this->getDefaultWorkspaceIntegrations($periodType);
            }

            $response = $this->client->index([
                'index' => $this->getIndexName($periodType),
                'id' => $this->generateDocumentId($periodType, $tenantId, $workspaceId, $timestamp),
                'body' => $data
            ]);

            return ['success' => true, 'id' => $response['_id'] ?? null, 'index' => $response['_index'] ?? null];

        } catch (\Throwable $e) {
            Log::channel('elasticsearch')->error('Failed to store document', [
                'error' => $e->getMessage(),
                'period_type' => $periodType,
                'tenant_id' => $tenantId
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Get document from Elasticsearch by ID.
     */
    protected function getDocument(string $periodType, int $tenantId, mixed $workspaceId, Carbon $timestamp): ?array
    {
        try {
            $response = $this->client->get([
                'index' => $this->getIndexName($periodType),
                'id' => $this->generateDocumentId($periodType, $tenantId, $workspaceId, $timestamp)
            ]);

            return $response['_source'] ?? null;

        } catch (\Throwable $e) {
            return null;
        }
    }
}
