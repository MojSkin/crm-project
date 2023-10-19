<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends _BaseModel
{
    use HasFactory;

//    protected $with = ['fields'];
    protected $casts = ['active' => 'boolean'];
    protected $fillable = ['title', 'description', 'active'];

    public $field_types = [
        'text' => [
            'id' => 1,
            'title' => 'متن تک خطی',
            'icon' => 'fal fa-horizontal-rule',
            'props' => [
                'min' => null,
                'max' => null,
            ],
            'label' => [
                'min' => 'حداقل طول رشته',
                'max' => 'حداکثر طول رشته',
            ],
            'hint' => [
                'min' => 'حداقل طول متن این فیلد را مشخص کنید. برای غیرفعال کردن، آن را خالی بگذارید',
                'max' => 'حداکثر طول متن این فیلد را مشخص کنید. برای غیرفعال کردن، آن را خالی بگذارید',
            ],
        ],
        'nCode' => [
            'id' => 11,
            'title' => 'کد ملی',
            'icon' => 'fal fa-user',
        ],
        'pCode' => [
            'id' => 12,
            'title' => 'کد پستی',
            'icon' => 'fal fa-user',
        ],
        'mobile' => [
            'id' => 13,
            'title' => 'شماره موبایل',
            'icon' => 'fal fa-mobile',
        ],
        'email' => [
            'id' => 14,
            'title' => 'پست الکترونیک',
            'icon' => 'fal fa-mailbox',
        ],
        'textarea' => [
            'id' => 2,
            'title' => 'متن چند خطی',
            'icon' => 'fal fa-grip-lines',
            'props' => [
                'min' => null,
                'max' => null,
                'max_rows' => null,
            ],
            'label' => [
                'min' => 'حداقل طول رشته',
                'max' => 'حداکثر طول رشته',
                'max_rows' => 'حداکثر تعداد خطوط',
            ],
            'hint' => [
                'min' => 'حداقل طول متن این فیلد را مشخص کنید. برای غیرفعال کردن، آن را خالی بگذارید',
                'max' => 'حداکثر طول متن این فیلد را مشخص کنید. برای غیرفعال کردن، آن را خالی بگذارید',
                'max_rows' => 'حداکثر تعداد سطرهای این فیلد را مشخص کنید. برای غیرفعال کردن، آن را خالی بگذارید.',
            ],
        ],
        'number' => [
            'id' => 3,
            'title' => 'عدد',
            'icon' => 'fab fa-creative-commons-zero',
            'props' => [
                'min' => null,
                'max' => null,
                'steps' => 1,
            ],
            'label' => [
                'min' => 'کمترین مقدار',
                'max' => 'بیشترین مقدار',
                'steps' => 'پلکان',
            ],
            'hint' => [
                'min' => 'کمترین عددی که در این فیلد می‌تواند قرار بگیرد را مشخص کنید. برای غیرفعال کردن، آن را خالی بگذارید',
                'max' => 'بیشترین عددی که در این فیلد می‌تواند قرار بگیرد را مشخص کنید. برای غیرفعال کردن، آن را خالی بگذارید',
                'steps' => 'پلکان‌های افزایش این فیلد را مشخص کنید. حداقل این عدد می‌بایست 1 باشد',
            ],
        ],
        'payment' => [
            'id' => 31,
            'title' => 'مبلغ',
            'icon' => 'fal fa-dollar-sign',
            'props' => [
                'options' => 0,
            ],
            'label' => [
                'options' => 'مبلغ مورد نظر',
            ],
            'hint' => [
                'options' => 'مبلغ مورد نظر خود را به ریال وارد کنید. پس از ثبت فرم، کاربر به درگاه پرداخت منتقل خواهد شد.',
            ],
        ],
        'date' => [
            'id' => 4,
            'title' => 'تاریخ',
            'icon' => 'fal fa-calendar-day',
            'props' => [
                'min' => null,
                'max' => null,
            ],
            'label' => [
                'min' => 'تاریخ شروع',
                'max' => 'تاریخ پایان',
            ],
            'hint' => [
                'min' => 'شروع بازه زمانی مجاز این فیلد را مشخص کنید. برای غیرفعال کردن، آن را خالی بگذارید',
                'max' => 'پایان بازه زمانی مجاز این فیلد را مشخص کنید. برای غیرفعال کردن، آن را خالی بگذارید',
            ],
        ],
        'datetime' => [
            'id' => 5,
            'title' => 'تاریخ و ساعت',
            'icon' => 'fal fa-calendar-alt',
            'props' => [
                'min' => null,
                'max' => null,
            ],
            'label' => [
                'min' => 'زمان شروع',
                'max' => 'زمان پایان',
            ],
            'hint' => [
                'min' => 'شروع بازه زمانی مجاز این فیلد را مشخص کنید. برای غیرفعال کردن، آن را خالی بگذارید',
                'max' => 'پایان بازه زمانی مجاز این فیلد را مشخص کنید. برای غیرفعال کردن، آن را خالی بگذارید',
            ],
        ],
        'time' => [
            'id' => 6,
            'title' => 'ساعت',
            'icon' => 'fal fa-clock',
            'props' => [
                'min' => null,
                'max' => null,
            ],
            'label' => [
                'min' => 'ساعت شروع',
                'max' => 'ساعت پایان',
            ],
            'hint' => [
                'min' => 'شروع بازه زمانی مجاز این فیلد را مشخص کنید. برای غیرفعال کردن، آن را خالی بگذارید',
                'max' => 'پایان بازه زمانی مجاز این فیلد را مشخص کنید. برای غیرفعال کردن، آن را خالی بگذارید',
            ],
        ],
        'checkbox' => [
            'id' => 7,
            'title' => 'گزینه چند انتخابی',
            'icon' => 'fal fa-check-square',
            'props' => [
                'options' => 'انتخاب یک|انتخاب دو|انتخاب سه'
            ],
            'label' => [
                'options' => 'انتخاب‌ها',
            ],
            'hint' => [
                'options' => 'گزینه‌ها را با علامت پایپ (|) از یکدیگر جدا کنید',
            ],
        ],
        'radio' => [
            'id' => 8,
            'title' => 'گزینه تک انتخابی',
            'icon' => 'fal fa-dot-circle',
            'props' => [
                'options' => 'انتخاب یک|انتخاب دو|انتخاب سه'
            ],
            'label' => [
                'options' => 'انتخاب‌ها',
            ],
            'hint' => [
                'options' => 'گزینه‌ها را با علامت پایپ (|) از یکدیگر جدا کنید',
            ],
        ],
        'select' => [
            'id' => 9,
            'title' => 'لیست تک انتخابی',
            'icon' => 'fal fa-mouse-pointer',
            'props' => [
                'options' => 'انتخاب یک|انتخاب دو|انتخاب سه'
            ],
            'label' => [
                'options' => 'انتخاب‌ها',
            ],
            'hint' => [
                'options' => 'گزینه‌ها را با علامت پایپ ( | ) از یکدیگر جدا کنید',
            ],
        ],
        'file' => [
            'id' => 15,
            'title' => 'فایل',
            'icon' => 'fal fa-upload',
            'props' => [
                'min' => null,
                'max' => null,
                'max_rows' => 1,
                'options' => '.jpg, .png',
            ],
            'label' => [
                'min' => 'حداقل حجم فایل',
                'max' => 'حداکثر حجم فایل',
                'max_rows' => 'حداکثر آپلود همزمان',
                'options' => 'فرمت‌های قابل قبول',
            ],
            'hint' => [
                'min' => 'حداقل حجمم مجاز برای هر فایل را در مقیاس <span class="has-text-danger is-weight-700">کیلوبایت</span> مشخص کنید. برای غیرفعال کردن، آن را خالی بگذارید',
                'max' => 'حداکثر حجمم مجاز برای هر فایل را در مقیاس <span class="has-text-danger is-weight-700">کیلوبایت</span> مشخص کنید. برای غیرفعال کردن، آن را خالی بگذارید',
                'max_rows' => 'حداکثر آپلودهای همزمان. این عدد بین 1 تا 10 مجاز بوده و خارج از این محدوده برابر با نزدیک‌ترین عدد مجاز خواهد بود.',
                'options' => 'فرمت‌های قابل قبول را با کامای انگلیسی ( , ) از یکدیگر جدا کنید'
            ],
        ],
        'html' => [
            'id' => 10,
            'title' => 'متن خام / HTML',
            'icon' => 'fal fa-code',
            'props' => [
                'options' => ''
            ],
            'label' => [
                'options' => 'متن پیام نمایشی',
            ],
            'hint' => [
                'options' => 'متنی که در این کادر نوشته می‌شود به همان صورت به کاربر نمایش داده می‌شود. کدهای HTML نیز قابل قبول هستند',
            ],
        ],
    ];

    public function fields() {
        return $this->hasMany(FormField::class)->orderBy('tab_index');
    }

    public function useCount() {
        return $this->hasMany(UsedForm::class, 'id', 'form_id');
    }
}
