<?php

header('Content-type: application/json');

echo '{
    "rajaongkir": {
        "query": {
            "origin": "152",
            "destination": "398",
            "weight": 1700,
            "courier": "jne"
        },
        "status": {
            "code": 200,
            "description": "OK"
        },
        "origin_details": {
            "city_id": "152",
            "province_id": "6",
            "province": "DKI Jakarta",
            "type": "Kota",
            "city_name": "Jakarta Pusat",
            "postal_code": "10540"
        },
        "destination_details": {
            "city_id": "398",
            "province_id": "10",
            "province": "Jawa Tengah",
            "type": "Kabupaten",
            "city_name": "Semarang",
            "postal_code": "50511"
        },
        "results": [
            {
                "code": "jne",
                "name": "Jalur Nugraha Ekakurir (JNE)",
                "costs": [
                    {
                        "service": "OKE",
                        "description": "Ongkos Kirim Ekonomis",
                        "cost": [
                            {
                                "value": 32000,
                                "etd": "2-3",
                                "note": ""
                            }
                        ]
                    },
                    {
                        "service": "REG",
                        "description": "Layanan Reguler",
                        "cost": [
                            {
                                "value": 36000,
                                "etd": "1-2",
                                "note": ""
                            }
                        ]
                    },
                    {
                        "service": "YES",
                        "description": "Yakin Esok Sampai",
                        "cost": [
                            {
                                "value": 56000,
                                "etd": "1-1",
                                "note": ""
                            }
                        ]
                    },
                    {
                        "service": "SPS",
                        "description": "Super Speed",
                        "cost": [
                            {
                                "value": 488000,
                                "etd": "",
                                "note": ""
                            }
                        ]
                    },
                    {
                        "service": "JTR",
                        "description": "JNE Trucking",
                        "cost": [
                            {
                                "value": 40000,
                                "etd": "",
                                "note": ""
                            }
                        ]
                    },
                    {
                        "service": "JTR250",
                        "description": "JNE Trucking",
                        "cost": [
                            {
                                "value": 1100000,
                                "etd": "",
                                "note": ""
                            }
                        ]
                    },
                    {
                        "service": "JTR<150",
                        "description": "JNE Trucking",
                        "cost": [
                            {
                                "value": 500000,
                                "etd": "",
                                "note": ""
                            }
                        ]
                    },
                    {
                        "service": "JTR>250",
                        "description": "JNE Trucking",
                        "cost": [
                            {
                                "value": 1500000,
                                "etd": "",
                                "note": ""
                            }
                        ]
                    }
                ]
            }
        ]
    }
}';