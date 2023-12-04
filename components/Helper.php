<?php

namespace app\components;

use app\models\MstBranch;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class Helper extends \mdm\admin\components\Helper
{

    const STAT_INACTIVE = 0;
    const STAT_ACTIVE = 1;
    const STAT_PENDING = 2;
    const STAT_RETUR = 3;
    const STAT_FAIL = 4;
    const STAT_CANCEL = 5;
    const GENDER_MAN = 'L';
    const GENDER_WOMAN = 'P';
    const SUBMIT_ACT = 'SUBMIT_ACT';
    const SUBMIT_SAVE = 'SUBMIT_SAVE';
    const SETTING_BRANCH = 'SETTING_BRANCH';

    public static function isGuest()
    {
        return Yii::$app->user ? Yii::$app->user->isGuest : true;
    }

    public static function identity()
    {
        return self::isGuest() ? NULL : Yii::$app->user->identity;
    }

    public static function getBaseUrl($file = NULL)
    {
        return Url::base(true) . '/' . $file;
    }

    public static function getBaseImg($file = NULL)
    {
        return self::getBaseUrl('images/' . $file);
    }

    public static function getBaseFile($file = NULL)
    {
        return self::getBaseUrl('uploads/' . $file);
    }

    public static function session($key, $set = NULL)
    {
        return $set !== null ? Yii::$app->session->set($key, $set) : Yii::$app->session->get($key);
    }

    public static function getFlash($key)
    {
        return Yii::$app->session->getFlash($key);
    }

    public static function setFlash($key, $set)
    {
        return Yii::$app->session->setFlash($key, $set);
    }

    public static function flashSucceed($msg = '')
    {
        return self::setFlash('success', (empty($msg) ? 'Proses berhasil.' : $msg));
    }

    public static function flashFailed($msg = '')
    {
        return self::setFlash('danger', (empty($msg) ? 'Proses gagal!' : $msg));
    }

    public static function encode($string)
    {
        // return mb_convert_encoding(trim($string), 'UTF-8', 'ISO-8859-1');
        return filter_var($string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    }

    public static function textStatus($stat = null)
    {
        $stats = ['NON-AKTIF', 'AKTIF'];
        return empty($stats[$stat]) ? $stats : $stats[$stat];
    }

    public static function filterValue($value)
    {
        return strlen($value) === 0 ? null : $value;
    }

    public static function textGender($stat = null)
    {
        $stats = [self::GENDER_MAN => 'L', self::GENDER_WOMAN => 'P'];
        return empty($stats[$stat]) ? $stats : $stats[$stat];
    }

    public static function calcPercent($val, $percent)
    {
        return ($val * $percent) / 100;
    }

    public static function textLabel($text, $stat)
    {
        $stats = [
            Helper::STAT_INACTIVE => 'danger',
            Helper::STAT_ACTIVE => 'success',
            Helper::STAT_PENDING => 'warning',
            Helper::STAT_RETUR => 'warning',
            Helper::STAT_FAIL => 'danger',
        ];
        $str = isset($stats[$stat]) ? $stats[$stat] : 'primary';
        return "<span class='badge badge-$str'>$text</span>";
    }

    public static function sumArray($arr, $key)
    {
        $sum = 0;
        foreach ($arr as $row) {
            $sum += is_object($row) ? $row->$key : $row[$key];
        }
        return $sum;
    }

    public static function getDates($month = null)
    {
        $date = [];
        for ($i = 1; $i <= ($month ?: date('m')); $i++) {
            $date[$i] = $i;
        }
        return $date;
    }

    public static function getMonths()
    {
        $month = [];
        setlocale(LC_TIME, 'id_ID.utf8');
        for ($i = 1; $i <= 12; $i++) {
            $month[$i] = $i == 2 ? 'Feb' : strftime('%b', strtotime('01-' . $i . '-2000'));
        }
        return $month;
    }

    public static function getYears()
    {
        $year = [];
        for ($i = ((int) date('Y')); $i <= ((int) date('Y')) + 1; $i++) {
            $year[$i] = $i;
        }
        return $year;
    }

    public static function diffHour($date, $hour = 48, $hourafter = 48)
    {
        $diff = date_diff(date_create(), date_create($date), false);
        $h = ($diff->y * 8760 + $diff->m * 30 * 24 + $diff->d * 24 + $diff->h + $diff->i / 60) * (1 - ($diff->invert * 2));
        return $h <= $hour && $h > (-1 * $hourafter);
    }

    public static function diffMinutes($date, $minutes = 48, $minutesafter = 48)
    {
        return self::diffHour($date, $minutes / 60, $minutesafter / 60);
    }

    public static function calcAge($dob, $ref = null)
    {
        $now = $ref ? $ref : time();
        return round(($now - strtotime($dob)) / (31557600), 2);
    }

    public static function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicated - symbols
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    //    CODE GENERATOR

    public static function getRandom($len, $format = 1)
    {
        $sets = ['0956731248', 'STUVWXGHNOPQRYZIJKLMABCDEF', 'ijrstuvwhnopqyzxklmabcdefg'];
        $seeds = [
            $sets[0], // numeric
            $sets[1], // uppercase
            $sets[2], // lowercase
            $sets[0] . $sets[1] . $sets[2], // all
            $sets[0] . $sets[1], //numeric uppercase
            $sets[0] . $sets[2] // numeric lowercase
        ];
        $key = $seeds[$format];
        $keyLen = strlen($key);
        $str = '';
        for ($i = 0; $i < $len; $i++) {
            $str .= $key[rand(0, $keyLen - 1)];
        }
        return $str;
    }

    public static function generateCode($id = NULL)
    {
        $salt = empty($id) ? date('dms') : $id;
        $code = self::getRandom(8 - strlen($salt)) . $salt;
        return $code;
    }

    public static function arrMapLower($model, $key, $value)
    {
        $locations = [];
        foreach ($model as $val) {
            $lowerName = trim(strtolower($val[$key]));
            $locations[$lowerName] = $val[$value];
        }

        return $locations;
    }

    public static function getInt($str)
    {
        return (int) preg_replace('/\D/', '', $str);
    }

    public static function upperTrim($str)
    {
        return strtolower(trim($str));
    }

    public static function lowerTrim($str)
    {
        return strtolower(trim($str));
    }

    public static function filterActionColumn($buttons = ['view} {update} {delete'], $user = null)
    {
        if (is_array($buttons)) {
            $result = [];
            foreach ($buttons as $button) {
                $result[] = "{{$button}}";
            }
            return implode(' ', $result);
        }
        return preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) use ($user) {
            return "{{$matches[1]}}";
        }, $buttons);

        if (is_array($buttons)) {
            $result = [];
            foreach ($buttons as $button) {
                if (static::checkRoute($button, [], $user)) {
                    $result[] = "{{$button}}";
                }
            }
            return implode(' ', $result);
        }
        return preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) use ($user) {
            return static::checkRoute($matches[1], [], $user) ? "{{$matches[1]}}" : '';
        }, $buttons);
    }

    public static function checkValidRoute($route, $html)
    {
        return self::checkRoute($route) ? $html : null;
    }

    public static function getCustomSummary($count, $pagination, $staticBegin = true)
    {
        $totalCount = $pagination->totalCount;
        $begin = $staticBegin ? 1 : $pagination->getPage() * $pagination->pageSize + 1;
        $end = $begin + $count - 1;
        if ($begin > $end) {
            $begin = $end;
        } else if ($end > $totalCount) {
            $end = $totalCount;
        }

        return "Menampilkan $begin-$end of $totalCount items.";
    }

    public static function getTitleGridview($title = null, $model = null)
    {
        $user = self::identity();
        $branch = MstBranch::find()
            ->andWhere(['id_branch' => $model->id_branch ?? $user->id_branch])
            ->cache()
            ->one();
        $options = [
            'class' => 'text-center',
            'style' => 'border: 0px solid;padding:0px',
            'colspan' => 99,
        ];

        $header = [
            [
                'columns' => [
                    [
                        'content' => '<h4 class="my-0 font-weight-100">' . $branch->name . '</h4>',
                        'tag' => 'td',
                        'options' => $options,
                    ],
                ],
            ],
            $title ? [
                'columns' => [
                    [
                        'content' => "<h4 class='my-0 font-weight-100'>$title</h4>",
                        'tag' => 'td',
                        'options' => $options,
                    ],
                ],
            ] : [],
            !empty($model->date_start) && !empty($model->date_end) ? [
                'columns' => [
                    [
                        'content' => 'Periode ' . Yii::$app->formatter->asDate($model->date_start, 'php:d-m-Y') . ' s.d.' . Yii::$app->formatter->asDate($model->date_end, 'php:d-m-Y'),
                        'tag' => 'td',
                        'options' => $options,
                    ],
                ],
            ] : [],
            [
                'columns' => [
                    [
                        'content' => 'Dibuat Tanggal : <b>' . date('d F Y') . '</b>',
                        'tag' => 'td',
                        'options' => $options,
                    ],
                ],
            ]
        ];

        return $header;
    }

    public static function penyebut($value)
    {
        $value = abs($value);
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($value < 12) {
            $temp = " " . $huruf[$value];
        } else if ($value < 20) {
            $temp = self::penyebut($value - 10) . " belas";
        } else if ($value < 100) {
            $temp = self::penyebut($value / 10) . " puluh" . self::penyebut($value % 10);
        } else if ($value < 200) {
            $temp = " seratus" . self::penyebut($value - 100);
        } else if ($value < 1000) {
            $temp = self::penyebut($value / 100) . " ratus" . self::penyebut($value % 100);
        } else if ($value < 2000) {
            $temp = " seribu" . self::penyebut($value - 1000);
        } else if ($value < 1000000) {
            $temp = self::penyebut($value / 1000) . " ribu" . self::penyebut($value % 1000);
        } else if ($value < 1000000000) {
            $temp = self::penyebut($value / 1000000) . " juta" . self::penyebut($value % 1000000);
        } else if ($value < 1000000000000) {
            $temp = self::penyebut($value / 1000000000) . " milyar" . self::penyebut(fmod($value, 1000000000));
        } else if ($value < 1000000000000000) {
            $temp = self::penyebut($value / 1000000000000) . " trilyun" . self::penyebut(fmod($value, 1000000000000));
        }

        return ucwords($temp);
    }

    public static function terbilang($value)
    {
        $res = trim(self::penyebut($value));

        if ($value < 0) {
            $res = "minus " . $res;
        }

        return $res;
    }

    public static function convertToRoman($integer)
    {
        // Convert the integer into an integer (just to make sure)
        $integer = intval($integer);
        $result = '';

        // Create a lookup array that contains all of the Roman numerals.
        $lookup = array(
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        );

        foreach ($lookup as $roman => $value) {
            // Determine the number of matches
            $matches = intval($integer / $value);

            // Add the same number of characters to the string
            $result .= str_repeat($roman, $matches);

            // Set the integer to be the remainder of the integer and the value
            $integer = $integer % $value;
        }

        // The Roman numeral should be built, return it
        return $result;
    }

    public static function setExportList($data)
    {
        if (!empty($data['beforeHeader'])) {
            $data['contentBefore'] = [];

            foreach ($data['beforeHeader'] as $v) {
                if (!empty($v['columns'])) {
                    $label = implode(' ', ArrayHelper::getColumn($v['columns'], 'content'));

                    $data['contentBefore'][] = [
                        'value' => $label,
                    ];
                }
            }
            unset($data['beforeHeader']);
        }

        if (!empty($data['columns'])) {
            foreach ($data['columns'] as $i => $_) {
                if (!empty($data['columns'][$i]['format'])) {
                    $data['columns'][$i]['format'] = 'text';
                }
            }
        }

        return $data;
    }

    public static function cGridExport($dataProvider, $columns, $title, $id = 'selector', $filterModel = null, $toggleAllData = true)
    {
        $btnExport = ExportMenu::widget([
            'filename' => self::slugify($title) . '-' . date('Ymd'),
            'dataProvider' => $dataProvider,
        ] + self::setExportList($columns));

        return GridView::widget(array_merge([
            'filterModel' => $filterModel,
            'dataProvider' => $dataProvider,
            'id' => "gridview-id-$id",
            'toolbar' => [
                $btnExport,
                $toggleAllData ? '{toggleData}' : '',
            ]
        ], $columns));
    }

    public static function getDueDate()
    {
        $dueDate =  date('Y-m-28');

        if (DBHelper::today() >= $dueDate) {
            $dueDate = date('Y-m-28', strtotime('+1 month'));
        }

        return $dueDate;
    }

    public static function cacheFlush()
    {
        \Yii::$app->cache->flush();
    }

    public static function faSearch($text = 'Search')
    {
        return "<i class='fa fa-search'></i> $text";
    }

    public static function faAdd($text = 'Tambah')
    {
        return "<i class='fa fa-plus'></i> $text";
    }

    public static function faSave($text = 'Save')
    {
        return "<i class='fa fa-save'></i> $text";
    }

    public static function faDownload($text = 'Download')
    {
        return "<i class='fa fa-download'></i> $text";
    }

    public static function faUpdate($text = 'Update')
    {
        return "<i class='fa fa-pencil-alt'></i> $text";
    }

    public static function faRefresh($text = 'Update')
    {
        return "<i class='fa fa-redo-alt'></i> $text";
    }

    public static function faUpload($text = 'Import')
    {
        return "<i class='fa fa-upload'></i> $text";
    }

    public static function faDelete($text = 'Delete')
    {
        return "<i class='fa fa-trash'></i> $text";
    }

    public static function faList($text = 'List')
    {
        return "<i class='fa fa-list'></i> $text";
    }

    public static function faPrint($text = 'Print')
    {
        return "<i class='fa fa-print'></i> $text";
    }

    public static function saveImgDataUrl($data_url, $path)
    {
        list($type, $data) = explode(';', $data_url);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);

        file_put_contents($path, $data);
    }

    public static function resetInteger($number)
    {
        $number = str_replace('.', '', $number);
        $number = str_replace(',', '.', $number);

        return $number;
    }
}
