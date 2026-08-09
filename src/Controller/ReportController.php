<?php

namespace App\Controller;

use App\Repository\DataRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class ReportController extends AbstractController
{
    // Define general ideal ranges for freshwater aquariums
    private array $idealRanges = [
        'temp' => ['min' => 24.0, 'max' => 28.0],
        'ph' => ['min' => 6.8, 'max' => 7.5],
        'kh' => ['min' => 4.0, 'max' => 8.0],
        'gh' => ['min' => 6.0, 'max' => 10.0],
        'no2' => ['min' => 0.0, 'max' => 0.5], // Nitrite should be very low
        'no3' => ['min' => 0.0, 'max' => 20.0], // Nitrate can be higher but controlled
        'cl2' => ['min' => 0.0, 'max' => 0.2], // Chlorine should be very low
    ];

    #[Route('/reports', name: 'app_report_index')]
    public function index(DataRepository $dataRepository, SerializerInterface $serializer): Response
    {
        // Fetch recent data (e.g., last 50 entries)
        $datas = $dataRepository->findBy([], ['createdAt' => 'DESC'], 50);

        $count = count($datas);
        $summary = [
            'count' => $count, // Ensure count is explicitly added to the summary array
            'average_temp' => 0.0,
            'average_ph' => 0.0,
            'average_kh' => 0.0,
            'average_gh' => 0.0,
            'average_no2' => 0.0,
            'average_no3' => 0.0,
            'average_cl2' => 0.0,
            'water_quality_score' => 0,
            'water_quality_status' => 'Unknown',
            'chart_data' => [],
        ];

        if ($count > 0) {
            $totalTemp = 0.0;
            $totalPh = 0.0;
            $totalKh = 0.0;
            $totalGh = 0.0;
            $totalNo2 = 0.0;
            $totalNo3 = 0.0;
            $totalCl2 = 0.0;
            $outOfRangeCount = 0;

            foreach ($datas as $data) {
                $totalTemp += $data->getTemp();
                $totalPh += $data->getPh();
                $totalKh += $data->getKh();
                $totalGh += $data->getGh();
                $totalNo2 += $data->getNo2();
                $totalNo3 += $data->getNo3();
                $totalCl2 += $data->getCl2();

                // Check for parameters outside ideal ranges
                if ($data->getTemp() < $this->idealRanges['temp']['min'] || $data->getTemp() > $this->idealRanges['temp']['max']) $outOfRangeCount++;
                if ($data->getPh() < $this->idealRanges['ph']['min'] || $data->getPh() > $this->idealRanges['ph']['max']) $outOfRangeCount++;
                if ($data->getKh() < $this->idealRanges['kh']['min'] || $data->getKh() > $this->idealRanges['kh']['max']) $outOfRangeCount++;
                if ($data->getGh() < $this->idealRanges['gh']['min'] || $data->getGh() > $this->idealRanges['gh']['max']) $outOfRangeCount++;
                if ($data->getNo2() < $this->idealRanges['no2']['min'] || $data->getNo2() > $this->idealRanges['no2']['max']) $outOfRangeCount++;
                if ($data->getNo3() < $this->idealRanges['no3']['min'] || $data->getNo3() > $this->idealRanges['no3']['max']) $outOfRangeCount++;
                if ($data->getCl2() < $this->idealRanges['cl2']['min'] || $data->getCl2() > $this->idealRanges['cl2']['max']) $outOfRangeCount++;

                // Prepare data for charting (simplified for now, can be extended)
                $summary['chart_data'][] = [
                    'date' => $data->getCreatedAt()?->format('Y-m-d H:i:s'),
                    'temp' => $data->getTemp(),
                    'ph' => $data->getPh(),
                    'no2' => $data->getNo2(),
                    'kh' => $data->getKh(),
                    'gh' => $data->getGh(),
                    'no3' => $data->getNo3(),
                    'cl2' => $data->getCl2(),
                ];
            }

            $summary['average_temp'] = $totalTemp / $count;
            $summary['average_ph'] = $totalPh / $count;
            $summary['average_kh'] = $totalKh / $count;
            $summary['average_gh'] = $totalGh / $count;
            $summary['average_no2'] = $totalNo2 / $count;
            $summary['average_no3'] = $totalNo3 / $count;
            $summary['average_cl2'] = $totalCl2 / $count;

            // Basic water quality evaluation (more entries out of range = worse score)
            $maxPossibleOutOfRange = $count * count($this->idealRanges);
            $score = $outOfRangeCount / $count; // Average out-of-range ratio per measurement

            if ($score <= 0.1) { // Less than 10% of parameters out of range on average
                $summary['water_quality_status'] = 'Excellent';
            } elseif ($score <= 0.25) { // Less than 25%
                $summary['water_quality_status'] = 'Good';
            } elseif ($score <= 0.5) { // Less than 50%
                $summary['water_quality_status'] = 'Fair';
            } else { // More than 50% out of range
                $summary['water_quality_status'] = 'Needs Attention';
            }
             // For display, let's use a simpler score out of 100 based on the ratio
            $summary['water_quality_score'] = max(0, 100 - ($outOfRangeCount / $maxPossibleOutOfRange) * 100);

        }

        return $this->render('report/index.html.twig', [
            'controller_name' => 'ReportController',
            'summary' => $summary,
            'ideal_ranges' => $this->idealRanges, // Pass ideal ranges for reference
        ]);
    }
}
