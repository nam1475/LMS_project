<?php



/** convert minutes to hours */

use App\Models\Cart;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

if(!function_exists('convertMinutesToHours')) {
    function convertMinutesToHours($minutes) {
        if($minutes == null){
            return 0;
        }
        $hours = floor($minutes / 60);
        $minutes = $minutes % 60;
        return sprintf('%dh %02dm', $hours, $minutes); // Returns format : 1h 30m
    }

}

if(!function_exists('user')) {
    function user() {
        return auth('web')->user();
    }
}


if(!function_exists('adminUser')) {
    function adminUser() {
        return auth('admin')->user();
    }
}

/** calculate cart total */
if(!function_exists('cartCount')) {
    function cartCount() {
        return Cart::where('user_id', user()?->id)->count();
    }
}


/** calculate cart total */
if(!function_exists('cartTotal')) {
    function cartTotal($couponCode = null) {
        $total = 0;
        $cart = Cart::where('user_id', user()->id)->get();

        foreach($cart as $item) {
            if($item->course->discount > 0) {
                $total += $item->course->discount;
            }else {
                $total += $item->course->price;
            }
        }

        $couponObject = new Coupon();
        if($couponCode) {
            $total = $couponObject->calculateTotalBasedOnType($couponCode, $total);
        }

        return $total;
    }
}


if(!function_exists('getDiscountAmount')){
    function getDiscountAmount($originalPrice, $couponCode) {
        $totalPriceWithCoupon = cartTotal($couponCode);
        $discountAmount = $originalPrice - $totalPriceWithCoupon;
        return $discountAmount;
    }
}

/** calculate cart total */
if(!function_exists('calculateCommission')) {
    function calculateCommission($amount, $commission) {
        return $amount == 0 ? 0 : ($amount * $commission) / 100;
    }
}

/** Sidebar Item Active */
if(!function_exists('sidebarItemActive')) {
    function sidebarItemActive(array $routes) {
        
        foreach($routes as $route) {
            if(request()->routeIs($route)) {
                return 'active';
            }
        }
    }
}

// if(!function_exists('diffModels')) {
//     function diffModels(Model $old, Model $new): array
//     {
//         $changes = [];
//         foreach ($old->getAttributes() as $key => $oldValue) {
//             if (!in_array($key, ['id', 'updated_at', 'created_at']) && array_key_exists($key, $new->getAttributes())) {
//                 $newValue = $new->{$key};
//                 if ($oldValue != $newValue) {
//                     $changes[$key] = [
//                         'from' => $oldValue,
//                         'to' => $newValue,
//                     ];
//                 }
//             }
//         }
//         return $changes;
//     }
// }

// if (! function_exists('diffModels')) {
//     function diffModels(Model $draft, Model $original): array
//     {
//         $diff = [];

//         foreach ($draft->getAttributes() as $key => $draftValue) {
//             // Bỏ qua các trường không cần so sánh
//             if (in_array($key, ['created_at', 'updated_at', 'is_current', 'is_published', 'published_at'])) {
//                 continue;
//             }
            
//             $originalValue = $original->{$key} ?? null;

//             // if ($draftValue != $originalValue) {
//             //     $diff[$key] = [
//             //         'original' => $originalValue,
//             //         'draft' => $draftValue,
//             //     ];
//             // }

//             // Nếu khác thì đưa vào diff
//             if ($draftValue !== $originalValue) {
//                 $diff[$key] = $originalValue;
//             } 
//             else {
//                 $diff[$key] = null; // ✅ Trường không đổi
//             }
//         }

//         return $diff;
//     }
// }

if (! function_exists('diffModels')) {
    function diffModels(?Model $draft, ?Model $original): array
    {
        if (!$draft && !$original) {
            return []; // cả hai đều null, không có gì để so sánh
        }

        $attributes = collect($draft?->getAttributes() ?? [])
            ->keys()
            ->merge(collect($original?->getAttributes() ?? [])->keys())
            ->unique();

        $diff = [];

        foreach ($attributes as $key) {
            // Bỏ qua các trường không cần so sánh
            if (in_array($key, ['created_at', 'updated_at', 'is_current', 'is_published', 'published_at'])) {
                continue;
            }

            $draftValue = $draft?->$key;
            $originalValue = $original?->$key;

            if ($draftValue !== $originalValue) {
                $diff[$key] = $originalValue;
            } else {
                $diff[$key] = null; // không thay đổi
            }
        }

        return $diff;
    }
}

if (! function_exists('compareChaptersWithNestedLessons')) {
    function compareChaptersWithNestedLessons($draftCourse, $mainCourse)
    {
        $results = [];

        // if($draftCourse->chapters->empty() || $mainCourse->chapters->empty()) {
        //     return [];
        // }
        
        // Tổng hợp tất cả các chapter uuid từ bản chính và bản nháp
        $chapterUuids = $draftCourse->chapters->pluck('uuid')
            ->merge($mainCourse?->chapters->pluck('uuid'))
            ->unique();

        foreach ($chapterUuids as $uuid) {
            $draftChapter = $draftCourse?->chapters->firstWhere('uuid', $uuid);
            $mainChapter = $mainCourse?->chapters->firstWhere('uuid', $uuid);
            
            $chapterDiff = [
                'chapter_draft' => $draftChapter?->only([
                    'id', 'title', 'uuid', 'description', 'course_id', 'order',
                ]),
                // 'chapter_draft' => Arr::except(
                //     $draftChapter?->toArray() ?? [],
                //     ['created_at', 'updated_at']
                // ),
                'diff' => diffModels($draftChapter, $mainChapter),
                'lessons' => []
            ];

            // Tổng hợp tất cả lesson uuid từ bản chính và nháp
            $lessonUuids = collect([
                $draftChapter?->lessons,
                $mainChapter?->lessons
            ])
                ->filter()
                ->flatMap(fn ($col) => $col->pluck('uuid'))
                ->unique();

            foreach ($lessonUuids as $lessonUuid) {
                $draftLesson = $draftChapter?->lessons->firstWhere('uuid', $lessonUuid);
                $mainLesson = $mainChapter?->lessons->firstWhere('uuid', $lessonUuid);

                $chapterDiff['lessons'][] = [
                    'lesson_draft' => $draftLesson?->only([
                        'id', 'title', 'slug', 'uuid', 'chapter_id', 'course_id',
                    ]),
                    // 'lesson_draft' => $draftLesson?->toArray(),
                    'diff' => diffModels($draftLesson, $mainLesson),
                ];
            }

            $results[] = $chapterDiff;
        }

        return $results;
    }
}


