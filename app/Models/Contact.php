<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Contact extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $prefix_text = [
        0 => [
            'value' => 0,
            'text' => 'نامشخص',
            'icon' => 'fas fa-ban'
        ],
        1 => [
            'value' => 1,
            'text' => 'آقای',
            'icon' => 'fas fa-male'
        ],
        2 => [
            'value' => 2,
            'text' => 'خانم',
            'icon' => 'fas fa-female'
        ],
        3 => [
            'value' => 3,
            'text' => 'شرکت',
            'icon' => 'fa fa-building'
        ],
        4 => [
            'value' => 4,
            'text' => 'سازمان',
            'icon' => 'fas fa-industry'
        ],
        5 => [
            'value' => 5,
            'text' => 'اداره',
            'icon' => 'fa fa-tasks'
        ],
    ];

    public $additional_infos = [
        'phone' => [
            'options' => [
                [
                    'key' => 'mobile',
                    'title' => 'همراه',
                    'label' => 'تلفن همراه',
                    'validations' => [
                        'rules' => 'required|numeric|min:11|max:11',
                        'max_length' => 11
                    ]
                ],
                [
                    'key' => 'home',
                    'title' => 'منزل',
                    'label' => 'تلفن منزل',
                    'validations' => [
                        'rules' => 'required|numeric|min:4|max:20',
                        'max_length' => 20
                    ]
                ],
                [
                    'key' => 'work',
                    'title' => 'کار',
                    'label' => 'تلفن کاری',
                    'validations' => [
                        'rules' => 'required|numeric|min:4|max:20',
                        'max_length' => 20
                    ]
                ],
                [
                    'key' => 'work int',
                    'title' => 'داخلی',
                    'label' => 'شماره داخلی',
                    'validations' => [
                        'rules' => 'required|numeric|min:1|max:20',
                        'max_length' => 20
                    ]
                ],
                [
                    'key' => 'home fax',
                    'title' => 'فکس منزل',
                    'label' => 'فکس منزل',
                    'validations' => [
                        'rules' => 'required|numeric|min:4|max:20',
                        'max_length' => 20
                    ]
                ],
                [
                    'key' => 'work fax',
                    'title' => 'فکس کاری',
                    'label' => 'فکس کاری',
                    'validations' => [
                        'rules' => 'required|numeric|min:4|max:20',
                        'max_length' => 20
                    ]
                ],
                [
                    'key' => 'other',
                    'title' => 'سایر',
                    'validations' => [
                        'rules' => 'required|numeric|min:4|max:20',
                        'max_length' => 20
                    ],
                    'is_other' => true
                ]
            ],
            'title' => 'تلفن',
        ],
        'email' => [
            'options' => [
                [
                    'key' => 'personal',
                    'title' => 'شخصی',
                    'label' => 'ایمیل شخصی',
                    'validations' => [
                        'rules' => 'required|email|min:5|max:200',
                        'max_length' => 200
                    ]
                ],
                [
                    'key' => 'work',
                    'title' => 'کاری',
                    'label' => 'ایمیل کاری',
                    'validations' => [
                        'rules' => 'required|email|min:5|max:200',
                        'max_length' => 200
                    ]
                ],
                [
                    'key' => 'other',
                    'title' => 'سایر',
                    'validations' => [
                        'rules' => 'required|email|min:5|max:200',
                        'max_length' => 200
                    ],
                    'is_other' => true
                ]
            ],
            'title' => 'ایمیل',
        ],
        'address' => [
            'options' => [
                [
                    'key' => 'home',
                    'title' => 'منزل',
                    'label' => 'آدرس منزل',
                    'validations' => [
                        'rules' => 'required|min:2|max:250',
                        'max_length' => 250
                    ]
                ],
                [
                    'key' => 'work',
                    'title' => 'کار',
                    'label' => 'آدرس محل کار',
                    'validations' => [
                        'rules' => 'required|min:2|max:250',
                        'max_length' => 250
                    ]
                ],
                [
                    'key' => 'other',
                    'title' => 'سایر',
                    'validations' => [
                        'rules' => 'required|min:2|max:250',
                        'max_length' => 250
                    ],
                    'is_other' => true
                ]
            ],
            'title' => 'آدرس',
        ],
        'date' => [
            'options' => [
                [
                    'key' => 'birth',
                    'title' => 'تولد',
                    'label' => 'تولد',
                    'validations' => [
                        'rules' => 'required',
                    ]
                ],
                [
                    'key' => 'marriage',
                    'title' => 'ازدواج',
                    'label' => 'ازدواج',
                    'validations' => [
                        'rules' => 'required',
                    ]
                ],
                [
                    'key' => 'meet',
                    'title' => 'آشنایی',
                    'label' => 'آشنایی',
                    'validations' => [
                        'rules' => 'required',
                    ]
                ],
                [
                    'key' => 'other',
                    'title' => 'سایر',
                    'validations' => [
                        'rules' => 'required',
                    ],
                    'is_other' => true
                ]
            ],
            'title' => 'تاریخ',
        ],
        'other' => [
            'options' => [
                [
                    'key' => 'website',
                    'title' => 'وبسایت',
                    'label' => 'وبسایت',
                    'validations' => [
                        'rules' => 'required|min:2|max:100|url',
                        'max_length' => 250
                    ]
                ],
                [
                    'key' => 'note',
                    'title' => 'توضیحات',
                    'label' => 'توضیحات',
                    'validations' => [
                        'rules' => 'required|min:5|max:250',
                        'max_length' => 250
                    ]
                ],
            ],
            'title' => 'سایر مشخصات',
        ]
    ];

    public static function getDefault($arrayName)
    {
        $self = new static;
        return $self->$arrayName;
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function details(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ContactDetail::class);
    }

    public function displayName(): string
    {
        $prefix = $this->prefix > 0 && $this->prefix_text[$this->prefix] && $this->prefix_text[$this->prefix]['title'] ? $this->prefix_text[$this->prefix]['title'] : '';
        if ($this->fName && $this->lName) {
            return $prefix . ' ' .$this->fName . ' ' . $this->lName;
        }
        return $prefix . ' ' .$this->fName;
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(ContactTag::class);
    }

    public function position()
    {
        return $this->hasOne(ContactPosition::class);
    }
}
