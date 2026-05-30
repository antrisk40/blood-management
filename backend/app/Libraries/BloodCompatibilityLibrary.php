<?php

namespace App\Libraries;

class BloodCompatibilityLibrary
{
    protected static $compatibilityMap = [
        'O-'  => ['O-'],
        'O+'  => ['O-', 'O+'],
        'A-'  => ['O-', 'A-'],
        'A+'  => ['O-', 'O+', 'A-', 'A+'],
        'B-'  => ['O-', 'B-'],
        'B+'  => ['O-', 'O+', 'B-', 'B+'],
        'AB-' => ['O-', 'A-', 'B-', 'AB-'],
        'AB+' => [
            'O-', 'O+',
            'A-', 'A+',
            'B-', 'B+',
            'AB-', 'AB+'
        ]
    ];

    /**
     * Check if a receiver can request a specific blood sample
     * 
     * @param string $receiverBloodGroup
     * @param string $sampleBloodGroup
     * @return bool
     */
    public static function canRequest(string $receiverBloodGroup, string $sampleBloodGroup): bool
    {
        if (!isset(self::$compatibilityMap[$receiverBloodGroup])) {
            return false;
        }

        return in_array($sampleBloodGroup, self::$compatibilityMap[$receiverBloodGroup]);
    }
}
