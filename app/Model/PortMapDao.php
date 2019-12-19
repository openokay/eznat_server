<?php

namespace App\Model;
use Illuminate\Support\Facades\DB;
class PortMapDao
{
    public function retrieve($where)
    {
        $clientList = DB::connection("sqlite")
            ->table("client")
            ->select('channel')
            ->get()->toArray();
        $tmp = [];
        foreach ($clientList as $client) {
            $tmp[] = $client->channel;
        }
        DB::connection("sqlite")
            ->table("port_map")
            ->whereNotIn('channel', $tmp)
            ->delete();

        return DB::connection("sqlite")
            ->table("port_map")
            ->join("client","port_map.channel", "=", "client.channel")
            ->where($where)
            ->orderBy("port_map.channel")
            ->select("port_map.*", "client.name as client")
            ->get();
    }
}
