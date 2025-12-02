<?php

namespace App\Helpers;

use Config;
use App\Models\ProjectType;
use App\Models\Department;
use App\Models\ProjectPriority;
use App\Models\ProjectStatus;
use App\Models\ProjectDeliveryFrequency;
use App\Models\ModeOfDelivery;
use App\Models\InputOutputFormat;
use App\Models\IndustryVertical;
use App\Models\ServiceOffering;
use App\Models\UnitOfMeasurement;
use App\Models\Currency;
use App\Models\Description;
use App\Models\User;
use App\Models\SkillMaster;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use App\Models\IntakeStatus;
use App\Models\IntakeQueryType;
use App\Models\IntakeLanguage;
use App\Models\IntakeLeaseType;
use App\Models\IntakeWorkType;
use App\Models\QueryStatus;
use App\Models\FeedbackCategory;
use App\Models\InvoiceFormat;
use App\Support\MoneyToWords;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ProjectCategory;

class Helpers
{
  public static function appClasses()
  {
    $data = config('custom.custom');

    // default data array
    $DefaultData = [
      'myLayout' => 'vertical',
      'myTheme' => 'theme-default',
      'myStyle' => 'light',
      'myRTLSupport' => true,
      'myRTLMode' => true,
      'hasCustomizer' => true,
      'showDropdownOnHover' => true,
      'displayCustomizer' => true,
      'menuFixed' => true,
      'menuCollapsed' => false,
      'navbarFixed' => true,
      'footerFixed' => false,
      'menuFlipped' => false,
      // 'menuOffcanvas' => false,
      'customizerControls' => [
        'rtl',
        'style',
        'layoutType',
        'showDropdownOnHover',
        'layoutNavbarFixed',
        'layoutFooterFixed',
        'themes',
      ],
      //   'defaultLanguage'=>'en',
    ];

    // if any key missing of array from custom.php file it will be merge and set a default value from dataDefault array and store in data variable
    $data = array_merge($DefaultData, $data);

    // All options available in the template
    $allOptions = [
      'myLayout' => ['vertical', 'horizontal', 'blank'],
      'menuCollapsed' => [true, false],
      'hasCustomizer' => [true, false],
      'showDropdownOnHover' => [true, false],
      'displayCustomizer' => [true, false],
      'myStyle' => ['light', 'dark'],
      'myTheme' => ['theme-default', 'theme-bordered', 'theme-semi-dark'],
      'myRTLSupport' => [true, false],
      'myRTLMode' => [true, false],
      'menuFixed' => [true, false],
      'navbarFixed' => [true, false],
      'footerFixed' => [true, false],
      'menuFlipped' => [true, false],
      // 'menuOffcanvas' => [true, false],
      'customizerControls' => [],
      // 'defaultLanguage'=>array('en'=>'en','fr'=>'fr','de'=>'de','pt'=>'pt'),
    ];

    //if myLayout value empty or not match with default options in custom.php config file then set a default value
    foreach ($allOptions as $key => $value) {
      if (array_key_exists($key, $DefaultData)) {
        if (gettype($DefaultData[$key]) === gettype($data[$key])) {
          // data key should be string
          if (is_string($data[$key])) {
            // data key should not be empty
            if (isset($data[$key]) && $data[$key] !== null) {
              // data key should not be exist inside allOptions array's sub array
              if (!array_key_exists($data[$key], $value)) {
                // ensure that passed value should be match with any of allOptions array value
                $result = array_search($data[$key], $value, 'strict');
                if (empty($result) && $result !== 0) {
                  $data[$key] = $DefaultData[$key];
                }
              }
            } else {
              // if data key not set or
              $data[$key] = $DefaultData[$key];
            }
          }
        } else {
          $data[$key] = $DefaultData[$key];
        }
      }
    }
    //layout classes
    $layoutClasses = [
      'layout' => $data['myLayout'],
      'theme' => $data['myTheme'],
      'style' => $data['myStyle'],
      'rtlSupport' => $data['myRTLSupport'],
      'rtlMode' => $data['myRTLMode'],
      'textDirection' => $data['myRTLMode'],
      'menuCollapsed' => $data['menuCollapsed'],
      'hasCustomizer' => $data['hasCustomizer'],
      'showDropdownOnHover' => $data['showDropdownOnHover'],
      'displayCustomizer' => $data['displayCustomizer'],
      'menuFixed' => $data['menuFixed'],
      'navbarFixed' => $data['navbarFixed'],
      'footerFixed' => $data['footerFixed'],
      'menuFlipped' => $data['menuFlipped'],
      // 'menuOffcanvas' => $data['menuOffcanvas'],
      'customizerControls' => $data['customizerControls'],
    ];

    // sidebar Collapsed
    if ($layoutClasses['menuCollapsed'] == true) {
      $layoutClasses['menuCollapsed'] = 'layout-menu-collapsed';
    }

    // Menu Fixed
    if ($layoutClasses['menuFixed'] == true) {
      $layoutClasses['menuFixed'] = 'layout-menu-fixed';
    }

    // Navbar Fixed
    if ($layoutClasses['navbarFixed'] == true) {
      $layoutClasses['navbarFixed'] = 'layout-navbar-fixed';
    }

    // Footer Fixed
    if ($layoutClasses['footerFixed'] == true) {
      $layoutClasses['footerFixed'] = 'layout-footer-fixed';
    }

    // Menu Flipped
    if ($layoutClasses['menuFlipped'] == true) {
      $layoutClasses['menuFlipped'] = 'layout-menu-flipped';
    }

    // Menu Offcanvas
    // if ($layoutClasses['menuOffcanvas'] == true) {
    //   $layoutClasses['menuOffcanvas'] = 'layout-menu-offcanvas';
    // }

    // RTL Supported template
    if ($layoutClasses['rtlSupport'] == true) {
      $layoutClasses['rtlSupport'] = '/rtl';
    }

    // RTL Layout/Mode
    if ($layoutClasses['rtlMode'] == true) {
      $layoutClasses['rtlMode'] = 'rtl';
      $layoutClasses['textDirection'] = 'rtl';
    } else {
      $layoutClasses['rtlMode'] = 'ltr';
      $layoutClasses['textDirection'] = 'ltr';
    }

    // Show DropdownOnHover for Horizontal Menu
    if ($layoutClasses['showDropdownOnHover'] == true) {
      $layoutClasses['showDropdownOnHover'] = 'true';
    } else {
      $layoutClasses['showDropdownOnHover'] = 'false';
    }

    // To hide/show display customizer UI, not js
    if ($layoutClasses['displayCustomizer'] == true) {
      $layoutClasses['displayCustomizer'] = 'true';
    } else {
      $layoutClasses['displayCustomizer'] = 'false';
    }

    return $layoutClasses;
  }

  public static function updatePageConfig($pageConfigs)
  {
    $demo = 'custom';
    if (isset($pageConfigs)) {
      if (count($pageConfigs) > 0) {
        foreach ($pageConfigs as $config => $val) {
          Config::set('custom.' . $demo . '.' . $config, $val);
        }
      }
    }
  }

  public static function getProjectMasterData()
  {
    return [
      'project_category' => ProjectCategory::where('status',1)->get(),
      'project_types' => ProjectType::where('status', 1)->get(),
      'departments' => Department::where('status', 1)->get(),
      'project_priorities' => ProjectPriority::where('status', 1)->get(),
      'project_statuses' => ProjectStatus::where('status', 1)->get(),
      'frequencies_of_delivery' => ProjectDeliveryFrequency::where('status', 1)->get(),
      'modes_of_delivery' => ModeOfDelivery::where('status', 1)->get(),
      'input_output_formats' => InputOutputFormat::where('status', 1)->get(),
      'customers' => Company::get(),
      'status' => IntakeStatus::get(),
      'intake_query' => IntakeQueryType::get(),
      'lease_types'  => IntakeLeaseType::get(),
      'work_types'   => IntakeWorkType::get(),
      'languages'    => IntakeLanguage::get(),
      'query_status' => QueryStatus::get(),
      'feedback_categories' => FeedbackCategory::get(),
      'invoice_formats'    => InvoiceFormat::get(),
      'industry_vertical' => IndustryVertical::where('status', 1)->get(),
      'service_offering' => ServiceOffering::where('status',1)->get()
    ];
  }

  public static function getDocumentMasterData()
  {
    return [
      'departments' => Department::where('status', 1)->get(),
      'industry_vertical' => IndustryVertical::where('status', 1)->get(),

    ];
  }

  /**Pricing Master */
  public static function getPricingMasterData()
  {
    return [
      'industry_vertical' => IndustryVertical::where('status', 1)->get(),
      'service_offering' => ServiceOffering::where('status', 1)->get(),
      'unit_of_measurement' => UnitOfMeasurement::where('status', 1)->get(),
      'currency' => Currency::where('status', 1)->get(),
      'description' => Description::where('status', 1)->get(),
      'departments' => Department::where('status', 1)->get(),
      'skills'      => SkillMaster::where('status', 1)->get(),
    ];
  }

  public static function getDisplayValue($key, $value)
  {
    return match ($key) {
      'unit_of_measurement_id' => UnitOfMeasurement::find($value)?->name ?? $value,
      'currency_id' => Currency::find($value)?->name ?? $value,
      'service_offering_id' => ServiceOffering::find($value)?->name ?? $value,
      'industry_vertical_id' => IndustryVertical::find($value)?->name ?? $value,
      'department_id' => Department::find($value)?->name ?? $value,
      'description_id' => Description::find($value)?->name ?? $value,
      'skills' => SkillMaster::find($value)?->name ?? $value,
      'created_by' => ($user = User::find($value)) ? trim($user->first_name . ' ' . $user->last_name) : $value,
      'updated_by' => ($user = User::find($value)) ? trim($user->first_name . ' ' . $user->last_name) : $value,
      default => $value,
    };
  }

  public static function getDisplayDocumentValue($key, $value)
  {
    return match ($key) {
      'customer_id' => Company::find($value)?->name ?? $value,
      'industry_vertical_id' => IndustryVertical::find($value)?->name ?? $value,
      'department_id' => Department::find($value)?->name ?? $value,
      default => $value,
    };
  }

  public static function getUsersByRole(string $roleName, array $columns = ['*'])
  {
    return User::role($roleName)->select($columns)->get();
  }

  public static function project_manager_ids(?int $projectId): array
  {
    if (empty($projectId)) return [];
    return DB::table('project_user')
      ->where('project_id', $projectId)
      ->pluck('user_id')
      ->unique()
      ->values()
      ->all();
  }

  public static function customer_point_of_contact_id(?int $projectId): array
  {
    if (empty($projectId)) return [];
    return DB::table('contact_project')
      ->where('project_id', $projectId)
      ->pluck('contact_id')
      ->unique()
      ->values()
      ->all();
  }
  public static function project_managers(?int $projectId)
  {
    $ids = self::project_manager_ids($projectId);
    if (empty($ids)) return collect();

    // Order by name; change to FIELD() if you want pivot order
    return User::whereIn('id', $ids)
      ->get();
  }

  public static function customer_point_of_contact(?int $projectId)
  {
    $ids = self::customer_point_of_contact_id($projectId);
    if (empty($ids)) return collect();

    // Order by name; change to FIELD() if you want pivot order
    return User::whereIn('id', $ids)
      ->get();
  }

  public static function getProjectManagersByIdsAndRole($ids, string $role)
  {
    $ids = is_array($ids) ? $ids : [$ids];

    return User::query()
      ->whereIn('project_manager', $ids)
      ->role($role) // Spatie scope
      ->get();
  }


  public static function subProjects($projectId)
  {
    // Get Active status ID from project_statuses table
    $activeStatusId = DB::table('project_statuses')
      ->whereRaw('LOWER(TRIM(name)) = ?', ['active'])
      ->value('id');

    $rows = DB::table('projects')
      ->where('parent_id', $projectId)
      ->whereNull('deleted_at')
      ->when($activeStatusId, function($query) use ($activeStatusId) {
        return $query->where('project_status_id', $activeStatusId);
      })
      ->orderBy('project_name')
      ->get([
        'id',
        'project_name as name',
        'parent_id'
      ]);

    return response()->json($rows);
  }


  public static function money_to_words($amount, string $currency = 'USD', string $locale = 'en'): string
  {
    return MoneyToWords::convert($amount, $currency, $locale);
  }

  public static function date_fmt($value, string $to = 'd-m-Y', ?string $from = null, ?string $tz = null): ?string
  {
    if (empty($value)) return null;
    try {
      if ($value instanceof \DateTimeInterface) {
        $dt = Carbon::instance(\DateTimeImmutable::createFromInterface($value));
      } elseif (is_numeric($value)) {
        $dt = Carbon::createFromTimestamp((int)$value);
      } elseif ($from) {
        $dt = Carbon::createFromFormat($from, trim((string)$value));
      } else {
        $norm = str_replace('/', '-', trim((string)$value));
        foreach (['Y-m-d', 'd-m-Y', 'd-m-y', 'm-d-Y', 'm-d-y', 'd M Y', 'M d, Y'] as $fmt) {
          try {
            $tmp = Carbon::createFromFormat($fmt, $norm);
            if ($tmp && $tmp->format($fmt) === $norm) {
              $dt = $tmp;
              break;
            }
          } catch (\Throwable $e) {
          }
        }
        $dt = $dt ?? Carbon::parse($norm);
      }
      if ($tz) $dt->setTimezone($tz);
      return $dt->format($to);
    } catch (\Throwable $e) {
      return null;
    }
  }
  public static function dmy_to(string $value, string $to = 'd-m-Y'): ?string
  {
    if ($value === '' || $value === null) return null;
    $norm = str_replace('/', '-', trim($value));
    $from = preg_match('/^\d{2}-\d{2}-\d{2}$/', $norm) ? 'd-m-y' : 'd-m-Y';
    try {
      return Carbon::createFromFormat($from, $norm)->format($to);
    } catch (\Throwable $e) {
      return null;
    }
  }

    public static function ymd_to_mdy(?string $date, string $default = ''): string
    {
        if (! $date) {
            return $default;
        }

        try {
            return Carbon::createFromFormat('Y-m-d', trim($date))->format('m-d-Y');
        } catch (\Throwable $e) {
            // Not in expected format; return original or default
            return $default !== '' ? $default : $date;
        }
    }

    public static function user_full_name($userOrId, string $fallback = '-', int $ttl = 600): string
    {
        if (empty($userOrId)) return $fallback;

        if ($userOrId instanceof User) {
            $first = trim((string) $userOrId->first_name);
            $last  = trim((string) $userOrId->last_name);
            $name  = trim($first.' '.$last);
            return $name !== '' ? $name : ($userOrId->name ?? $fallback);
        }

        $id = (int) $userOrId;
        if ($id <= 0) return $fallback;

        $usesSoftDeletes = in_array(SoftDeletes::class, class_uses_recursive(User::class), true);
        $query = $usesSoftDeletes ? User::withTrashed() : User::query();

        $user = $query->where('id', $id)->first(['first_name','last_name']);
        if (! $user) return $fallback;

        $first = trim((string) $user->first_name);
        $last  = trim((string) $user->last_name);
        $name  = trim($first.' '.$last);

        return $name !== '' ? $name : ($user->name ?? $fallback);
    }
}
