<?php

namespace SpeechPHP;

use SpeechPHP\Core\WavParser;
use SpeechPHP\Synthesis\WaveGenerator;
use SpeechPHP\Features\AudioFeatures;
use NumPHP\Core\NDArray;

class SpeechPHP
{
    /**
     * Read a .wav file and return its amplitude data and sample rate.
     *
     * @param string $filepath
     * @return array ['rate' => int, 'data' => NDArray]
     */
    public static function read(string $filepath): array
    {
        return WavParser::read($filepath);
    }

    /**
     * Write an array of amplitude data to a .wav file.
     *
     * @param string $filepath
     * @param int $sampleRate
     * @param NDArray|array $data
     * @return bool
     */
    public static function write(string $filepath, int $sampleRate, $data): bool
    {
        return WaveGenerator::write($filepath, $sampleRate, $data);
    }

    /**
     * Generate a simple sine wave tone.
     *
     * @param float $frequency Frequency in Hz (e.g. 440 for A4)
     * @param float $duration Duration in seconds
     * @param int $sampleRate Sample rate in Hz
     * @return NDArray
     */
    public static function generate_tone(float $frequency, float $duration, int $sampleRate = 44100): NDArray
    {
        return WaveGenerator::generateTone($frequency, $duration, $sampleRate);
    }

    /**
     * Calculate the Zero-Crossing Rate of an audio signal.
     * Used often in speech recognition to distinguish voiced/unvoiced speech.
     */
    public static function zero_crossing_rate($data): float
    {
        return AudioFeatures::zcr($data);
    }

    /**
     * Calculate the Root Mean Square (RMS) energy of an audio signal.
     * Represents the loudness/volume.
     */
    public static function rms_energy($data): float
    {
        return AudioFeatures::rms($data);
    }
}
