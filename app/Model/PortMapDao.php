<?php

namespace App\Model;
use Illuminate\Support\Facades\DB;
class PortMapDao
{
    public function retrieve($where)
    {
        $connection = DB::connection("sqlite");
        $clientList = $connection
            ->table("client")
            ->select('channel')
            ->get()->toArray();
        $tmp = [];
        foreach ($clientList as $client) {
            $tmp[] = $client->channel;
        }
        $connection->table("port_map")
            ->whereNotIn('channel', $tmp)
            ->delete();

        return $connection
            ->table("port_map")
            ->join("client","port_map.channel", "=", "client.channel")
            ->where($where)
            ->orderBy("port_map.channel")
            ->select("port_map.*", "client.name as client", "is_online")
            ->get();

    }
}
