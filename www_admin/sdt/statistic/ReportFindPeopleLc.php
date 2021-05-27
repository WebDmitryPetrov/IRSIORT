<?php
require_once 'AbstrctReport.php';

/**
 * Created by PhpStorm.
 * User: m.kulebyakin
 * Date: 23.06.2016
 * Time: 10:58
 * Îò÷åò 12
 */
class ReportFindPeopleLc extends AbstractReport
{

    public function execute($people)
    {
        $parsed = $this->parsePeople($people);

        $result = [
            'found' => [],
            'not_found' => [],
            'error' => $parsed['error']
        ];

        foreach ($parsed['people'] as $p) {
            $mans = ActPeople::Search($p['s'], null, $p['f'], $p['d'], false, [
                'exactSearch' => false,
                'docTypeSearch' => ActMan::DOCUMENT_CERTIFICATE,
                'testTypeSearch' => 2,
            ]);
            if (count($mans)) {
                $p['found'] = $mans;
                $result['found'][] = $p;
            } else {
                $result['not_found'][] = $p;
            }
//            var_dump($man);die;
        }

        return $result;


    }


    private function parsePeople($string)
    {
        $strings = explode("\n", trim($string));
//        var_dump($strings);
        $strings = array_map('trim', $strings);
        $strings = array_filter($strings);


//        die(var_dump($strings));

        $parsed = [
            'people' => [],
            'error' => [],
        ];
        foreach ($strings as $string) {
//            var_dump($string);
            $res = preg_match('|^(\S+)\s+([\S ]+)\s?(\d+.\d+.\d+)?$|', $string, $matches);
//            die(var_dump($matches));
            if ($res) {
                $parsed['people'][] = [
                    's' => $matches[1],
                    'f' => $matches[2],
                    'd' => isset($matches[3])?$matches[3]:null,
                    'found' => false,
                ];
            } else {
                $parsed['error'][] = $string;
            }

        }

        return $parsed;
    }
}