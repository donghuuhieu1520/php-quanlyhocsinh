<?php


namespace App\Helper;

use Moment\Moment;
use Moment\MomentException;
use Moment\MomentPeriodVo;

class Now
{
  /**
   * the moment instance used for parse
   * @var Moment
   */
  private Moment $moment;

  public function __construct(string $timeZone = 'Asia/Ho_Chi_Minh')
  {
    $this->moment = new Moment('now', $timeZone);
  }

  public function weekStart(string $format = 'd-m-Y') : string
  {
    return $this->moment
        ->getPeriod('week')
        ->getStartDate()
        ->format($format);
  }

  public function weekEnd(string $format = 'd-m-Y') : string
  {
    return $this->moment
        ->getPeriod('week')
        ->getEndDate()
        ->format($format);
  }

  public function day(string $format = 'd-m-Y') : string
  {
    return $this->moment->format($format);
  }

  public function month() : string
  {
    return $this->moment->getMonth();
  }
}
