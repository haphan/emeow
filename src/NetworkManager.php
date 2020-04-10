<?php

namespace Emeow;

class NetworkManager
{
    public function __construct()
    {

    }

    public function getAddressesOfNetworkInterface(string $interface): array
    {
        $addresses = [];

        foreach (net_get_interfaces() as $ifname => $data) {
            if($interface != $ifname) {
                continue;
            }

            $addresses = array_map(function(array $row) {
                return $row['address'] ?? null;
            }, $data['unicast']);
        }

        return array_filter($addresses);
    }
}