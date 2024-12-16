<?php

    function calculateSSSRegEE($status, $bz, $bo) {
        $value = 0;

        if ($status === "ACTIVE") {
            if ($bz > 0 && $bz <= 4249.99) {
                $value += 180;
            } elseif ($bz >= 4250 && $bz <= 4749.99) {
                $value += 202.5;
            } elseif ($bz >= 4750 && $bz <= 5249.99) {
                $value += 225;
            } elseif ($bz >= 5250 && $bz <= 5749.99) {
                $value += 247.5;
            } elseif ($bz >= 5750 && $bz <= 6249.99) {
                $value += 270;
            } elseif ($bz >= 6250 && $bz <= 6749.99) {
                $value += 292.5;
            } elseif ($bz >= 6750 && $bz <= 7249.99) {
                $value += 315;
            } elseif ($bz >= 7250 && $bz <= 7749.99) {
                $value += 337.5;
            } elseif ($bz >= 7750 && $bz <= 8249.99) {
                $value += 360;
            } elseif ($bz >= 8250 && $bz <= 8749.99) {
                $value += 382.5;
            } elseif ($bz >= 8750 && $bz <= 9249.99) {
                $value += 405;
            } elseif ($bz >= 9250 && $bz <= 9749.99) {
                $value += 427.5;
            } elseif ($bz >= 9750 && $bz <= 10249.99) {
                $value += 450;
            } elseif ($bz >= 10250 && $bz <= 10749.99) {
                $value += 472.5;
            } elseif ($bz >= 10750 && $bz <= 11249.99) {
                $value += 495;
            } elseif ($bz >= 11250 && $bz <= 11749.99) {
                $value += 517.5;
            } elseif ($bz >= 11750 && $bz <= 12249.99) {
                $value += 540;
            } elseif ($bz >= 12250 && $bz <= 12749.99) {
                $value += 562.5;
            } elseif ($bz >= 12750 && $bz <= 13249.99) {
                $value += 585;
            } elseif ($bz >= 13250 && $bz <= 13749.99) {
                $value += 607.5;
            } elseif ($bz >= 13750 && $bz <= 14249.99) {
                $value += 630;
            } elseif ($bz >= 14250 && $bz <= 14749.99) {
                $value += 652.5;
            } elseif ($bz >= 14750 && $bz <= 15249.99) {
                $value += 675;
            } elseif ($bz >= 15250 && $bz <= 15749.99) {
                $value += 697.5;
            } elseif ($bz >= 15750 && $bz <= 16249.99) {
                $value += 720;
            } elseif ($bz >= 16250 && $bz <= 16749.99) {
                $value += 742.5;
            } elseif ($bz >= 16750 && $bz <= 17249.99) {
                $value += 765;
            } elseif ($bz >= 17250 && $bz <= 17749.99) {
                $value += 787.5;
            } elseif ($bz >= 17750 && $bz <= 18249.99) {
                $value += 810;
            } elseif ($bz >= 18250 && $bz <= 18749.99) {
                $value += 832.5;
            } elseif ($bz >= 18750 && $bz <= 19249.99) {
                $value += 855;
            } elseif ($bz >= 19250 && $bz <= 19749.99) {
                $value += 877.5;
            } elseif ($bz >= 19750) {
                $value += 900;
            }
        } elseif ($status === "HOLD") {
            $value = 0;
        } else {
            $value = $bo;
        }

        return $value;
    }

    function calculateSSSMPFEE($status, $bz, $bp) {
        $value = 0;
    
        if ($status === "ACTIVE") {
            if ($bz > 0 && $bz <= 20249.99) {
                $value += 0;
            } elseif ($bz >= 20250 && $bz <= 20749.99) {
                $value += 22.5;
            } elseif ($bz >= 20750 && $bz <= 21249.99) {
                $value += 45;
            } elseif ($bz >= 21250 && $bz <= 21749.99) {
                $value += 67.5;
            } elseif ($bz >= 21750 && $bz <= 22249.99) {
                $value += 90;
            } elseif ($bz >= 22250 && $bz <= 22749.99) {
                $value += 112.5;
            } elseif ($bz >= 22750 && $bz <= 23249.99) {
                $value += 135;
            } elseif ($bz >= 23250 && $bz <= 23749.99) {
                $value += 157.5;
            } elseif ($bz >= 23750 && $bz <= 24249.99) {
                $value += 180;
            } elseif ($bz >= 24250 && $bz <= 24749.99) {
                $value += 202.5;
            } elseif ($bz >= 24750 && $bz <= 25249.99) {
                $value += 225;
            } elseif ($bz >= 25250 && $bz <= 25749.99) {
                $value += 247.5;
            } elseif ($bz >= 25750 && $bz <= 26249.99) {
                $value += 270;
            } elseif ($bz >= 26250 && $bz <= 26749.99) {
                $value += 292.5;
            } elseif ($bz >= 26750 && $bz <= 27249.99) {
                $value += 315;
            } elseif ($bz >= 27250 && $bz <= 27749.99) {
                $value += 337.5;
            } elseif ($bz >= 27750 && $bz <= 28249.99) {
                $value += 360;
            } elseif ($bz >= 28250 && $bz <= 28749.99) {
                $value += 382.5;
            } elseif ($bz >= 28750 && $bz <= 29249.99) {
                $value += 405;
            } elseif ($bz >= 29250 && $bz <= 29749.99) {
                $value += 427.5;
            } elseif ($bz >= 29750) {
                $value += 450;
            }
        } elseif ($status === "HOLD") {
            $value = 0;
        } else {
            $value = $bp;
        }
    
        return $value;
    }

    function calculatePHICEE($status, $l, $bq) {
        $value = 0;
    
        if ($status === "ACTIVE") {
            if ($l == 0) {
                $value = 0;
            } elseif ($l <= 10000) {
                $value = 500 / 2;
            } elseif ($l > 10000 && $l < 100000) {
                $value = ($l * 0.05) / 2;
            } elseif ($l >= 100000) {
                $value = 5000 / 2;
            }
        } elseif ($status === "HOLD") {
            $value = 0;
        } else {
            $value = $bq;
        }
    
        return $value;
    }

    function calculateHDMFEE($status, $l, $br) {
        $value = 0;
    
        if ($status === "ACTIVE") {
            if ($l < 1500) {
                $value = $l * 0.02;
            } elseif ($l >= 1500 && $l < 5000) {
                $value = $l * 0.02;
            } elseif ($l >= 5000) {
                $value = 5000 * 0.04;
            }
        } elseif ($status === "HOLD") {
            $value = 0;
        } else {
            $value = $br;
        }
    
        return $value;
    }

    function calculateSSSRegER($status, $bz, $bs) {
        $value = 0;
    
        if ($status === "ACTIVE") {
            if ($bz > 0 && $bz <= 4249.99) {
                $value = 380;
            } elseif ($bz >= 4250 && $bz <= 4749.99) {
                $value = 427.5;
            } elseif ($bz >= 4750 && $bz <= 5249.99) {
                $value = 475;
            } elseif ($bz >= 5250 && $bz <= 5749.99) {
                $value = 522.5;
            } elseif ($bz >= 5750 && $bz <= 6249.99) {
                $value = 570;
            } elseif ($bz >= 6250 && $bz <= 6749.99) {
                $value = 617.5;
            } elseif ($bz >= 6750 && $bz <= 7249.99) {
                $value = 665;
            } elseif ($bz >= 7250 && $bz <= 7749.99) {
                $value = 712.5;
            } elseif ($bz >= 7750 && $bz <= 8249.99) {
                $value = 760;
            } elseif ($bz >= 8250 && $bz <= 8749.99) {
                $value = 807.5;
            } elseif ($bz >= 8750 && $bz <= 9249.99) {
                $value = 855;
            } elseif ($bz >= 9250 && $bz <= 9749.99) {
                $value = 902.5;
            } elseif ($bz >= 9750 && $bz <= 10249.99) {
                $value = 950;
            } elseif ($bz >= 10250 && $bz <= 10749.99) {
                $value = 997.5;
            } elseif ($bz >= 10750 && $bz <= 11249.99) {
                $value = 1045;
            } elseif ($bz >= 11250 && $bz <= 11749.99) {
                $value = 1092.5;
            } elseif ($bz >= 11750 && $bz <= 12249.99) {
                $value = 1140;
            } elseif ($bz >= 12250 && $bz <= 12749.99) {
                $value = 1187.5;
            } elseif ($bz >= 12750 && $bz <= 13249.99) {
                $value = 1235;
            } elseif ($bz >= 13250 && $bz <= 13749.99) {
                $value = 1282.5;
            } elseif ($bz >= 13750 && $bz <= 14249.99) {
                $value = 1330;
            } elseif ($bz >= 14250 && $bz <= 14749.99) {
                $value = 1377.5;
            } elseif ($bz >= 14750 && $bz <= 15249.99) {
                $value = 1425;
            } elseif ($bz >= 15250 && $bz <= 15749.99) {
                $value = 1472.5;
            } elseif ($bz >= 15750 && $bz <= 16249.99) {
                $value = 1520;
            } elseif ($bz >= 16250 && $bz <= 16749.99) {
                $value = 1567.5;
            } elseif ($bz >= 16750 && $bz <= 17249.99) {
                $value = 1615;
            } elseif ($bz >= 17250 && $bz <= 17749.99) {
                $value = 1662.5;
            } elseif ($bz >= 17750 && $bz <= 18249.99) {
                $value = 1710;
            } elseif ($bz >= 18250 && $bz <= 18749.99) {
                $value = 1757.5;
            } elseif ($bz >= 18750 && $bz <= 19249.99) {
                $value = 1805;
            } elseif ($bz >= 19250 && $bz <= 19749.99) {
                $value = 1852.5;
            } elseif ($bz >= 19750) {
                $value = 1900;
            }
        } elseif ($status === "HOLD") {
            $value = 0;
        } else {
            $value = $bs;
        }
    
        return $value;
    }
    
    function calculateSSSMPFER($status, $bz, $bt) {
        $value = 0;
    
        if ($status === "ACTIVE") {
            if ($bz > 0 && $bz <= 20249.99) {
                $value = 0;
            } elseif ($bz >= 20250 && $bz <= 20749.99) {
                $value = 47.5;
            } elseif ($bz >= 20750 && $bz <= 21249.99) {
                $value = 95;
            } elseif ($bz >= 21250 && $bz <= 21749.99) {
                $value = 142.5;
            } elseif ($bz >= 21750 && $bz <= 22249.99) {
                $value = 190;
            } elseif ($bz >= 22250 && $bz <= 22749.99) {
                $value = 237.5;
            } elseif ($bz >= 22750 && $bz <= 23249.99) {
                $value = 285;
            } elseif ($bz >= 23250 && $bz <= 23749.99) {
                $value = 332.5;
            } elseif ($bz >= 23750 && $bz <= 24249.99) {
                $value = 380;
            } elseif ($bz >= 24250 && $bz <= 24749.99) {
                $value = 427.5;
            } elseif ($bz >= 24750 && $bz <= 25249.99) {
                $value = 475;
            } elseif ($bz >= 25250 && $bz <= 25749.99) {
                $value = 522.5;
            } elseif ($bz >= 25750 && $bz <= 26249.99) {
                $value = 570;
            } elseif ($bz >= 26250 && $bz <= 26749.99) {
                $value = 617.5;
            } elseif ($bz >= 26750 && $bz <= 27249.99) {
                $value = 665;
            } elseif ($bz >= 27250 && $bz <= 27749.99) {
                $value = 712.5;
            } elseif ($bz >= 27750 && $bz <= 28249.99) {
                $value = 760;
            } elseif ($bz >= 28250 && $bz <= 28749.99) {
                $value = 807.5;
            } elseif ($bz >= 28750 && $bz <= 29249.99) {
                $value = 855;
            } elseif ($bz >= 29250 && $bz <= 29749.99) {
                $value = 902.5;
            } elseif ($bz >= 29750) {
                $value = 950;
            }
        } elseif ($status === "HOLD") {
            $value = 0;
        } else {
            $value = $bt;
        }
    
        return $value;
    }
    
    function calculateSSSEC($status, $bz, $bu) {
        $value = 0;
    
        if ($status === "ACTIVE") {
            if ($bz > 0 && $bz <= 14749.99) {
                $value = 10;
            } elseif ($bz >= 14750) {
                $value = 30;
            }
        } elseif ($status === "HOLD") {
            $value = 0;
        } else {
            $value = $bu;
        }
    
        return $value;
    }
?>