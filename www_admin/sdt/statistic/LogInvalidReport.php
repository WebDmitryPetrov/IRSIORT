<?php

namespace SDT\statistic;

class LogInvalidReport extends \AbstractReport
{
    public function execute(\DateTime $from, \DateTime $to)
    {
        $connection = \Connection::getInstance();
        $sql = "select * from log_login_invalid lli where lli.created_at
            BETWEEN '%s' AND '%s'";
        $result = $connection->query(
            vsprintf(
                $sql,
                [
                    $from->format('Y-m-d 0:0:0'),
                    $to->format('Y-m-d 23:59:59'),
                ]
            )
        );
//        var_dump(count($result));
        /*
      array (size=10)
          'id' => string '354' (length=3)
          'login' => string 'center_1379' (length=11)
          'created_at' => string '2020-05-18 08:11:33' (length=19)
          'ip' => string '217.118.90.59' (length=13)
          'blocked' => string '0' (length=1)
          'user_id' => string '5799' (length=4)
          'head_id' => string '7' (length=1)
          'user_name' => string 'Ãðóïïà êîìïàíèé "ÐÓÑÒÝÊ" ôèëèàë â ã. Óëüÿíîâñê' (length=46)
          'head_name' => string 'ÐÓÄÍ - Òåñò ØÊ' (length=14)
          'lc_id' => string '1379' (length=4)
     */
        $grouped = [];
        do {
            $first_row = array_shift($result);
            $group = new GroupEl($first_row);
//            $group->parseRow();
            $grouped[] = $group;
            foreach ($result as $key => $value) {
                $parseResult = $group->parseRow($value);
                if ($parseResult === GroupEl::PARSE_TIMEOUT) {
                    break;
                }
                if ($parseResult === GroupEl::PARSE_OK) {
                    unset($result[$key]);
                }
                if ($parseResult === GroupEl::PARSE_SKIP) {
                    continue;
                }
            }
        } while (count($result));
//        var_dump(iterator_to_array(GroupFlatten($grouped)));
//        die;
        return $grouped;
//        return iterator_to_array(GroupFlatten($grouped));
    }
}

class GroupEl
{
    const GROUP_DURATION = 5 * 60;
    const AUTH_DURATION = 5 * 60;

    const PARSE_SKIP = 'skip';
    const PARSE_TIMEOUT = 'timeout';
    const PARSE_OK = 'ok';

    private $ip;
    private $items = [];
    private $firstAccessDt;
    private $lastAccessDt;

    public function __construct($row)
    {
        $this->ip = $row['ip'];
        $this->items[] = $row;
        $this->firstAccessDt = new \DateTimeImmutable($row['created_at']);
        $this->lastAccessDt = new \DateTimeImmutable($row['created_at']);
    }

    public function parseRow($row)
    {
        $limit = $this->lastAccessDt->add(new \DateInterval('PT'.self::GROUP_DURATION.'S'));
        $dt = new \DateTimeImmutable($row['created_at']);
        if ($dt > $limit) {
//            var_dump($dt,$limit);die;
            return self::PARSE_TIMEOUT;
        }
        if ($row['ip'] === $this->ip) {
            $this->items[] = $row;
            $this->lastAccessDt = $dt;

            return self::PARSE_OK;
        }
        if ($row['ip'] !== $this->ip) {
            return self::PARSE_SKIP;
        }

        return self::PARSE_TIMEOUT;
    }

    public function getItems()
    {
        $last = count($this->items);
        $authRows = $this->findAuth();
        if ($authRows) {
            $last += count($authRows);
//            $authRow['last'] = true;
//            $authRow['auth'] = true;
        }
        $itemKey = 0;
        foreach ($this->items as $key => $item) {
            $itemKey++;
            $item['last'] = $itemKey == $last;
            $item['auth'] = false;
            yield $item;
        }
        if ($authRows) {
            foreach ($authRows as $key => $item) {
                $itemKey++;
                $item['last'] = $itemKey == $last;
                $item['auth'] = true;
                yield $item;
            }
        }
    }

    /**
     *
     * @return array|null
     */
    private function findAuth()
    {
        $limit = $this->lastAccessDt->add(new \DateInterval('PT'.self::AUTH_DURATION.'S'));
        $C = \Connection::getInstance();
        $sql = "select l.*, 
       if(su.id is not null, u.surname , concat(u.surname, ' ', u.firstname,' ',u.fathername))       as user_name, 
       0 as blocked,
       if(su.id is not null, \"local_center\" , ut.caption) as utype,
       su.id as lc_id,
       su.name as univer,
       IF(hct.login_page_title IS NOT NULL AND hct.login_page_title <> '', hct.login_page_title, shc.short_name ) as head_name


from log_login l
left join tb_users u on u.u_id = l.user_id
left join user_type ut on ut.id = u.user_type_id
left join sdt_university su on u.univer_id = su.id
left join sdt_head_center shc on shc.id = l.head_id
left join  head_center_text hct on shc.id = hct.head_id
where l.created_at >= '%s' and l.created_at <='%s'   
and l.ip = '%s'
order by l.created_at;
";
        $sql = vsprintf(
            $sql,
            [
                $this->firstAccessDt->format('Y-m-d H:i:s'),
                $limit->format('Y-m-d  H:i:s'),
                $this->ip,
            ]
        );

//echo $sql;
        $res = $C->query($sql);
//var_dump($res);;
        return $res;


    }
}

/**
 * @param GroupEl[] $groups
 */
//function GroupFlatten(array $groups)
//{
//    foreach ($groups as $item) {
//        foreach ($item->getItems() as $row) {
//            yield $row;
//        }
//    }
//}