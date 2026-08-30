<?php

namespace Efati\ModuleGenerator\Tests\Casts;

use Carbon\Carbon;
use Efati\ModuleGenerator\Casts\GoliDateCast;
use Efati\ModuleGenerator\Support\Goli;
use Efati\ModuleGenerator\Support\HasGoliDates;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;

class GoliDateCastTest extends TestCase
{
    public function testConfiguredAndRuntimeCastsAreRegistered(): void
    {
        $model = new GoliDateCastModel();

        $this->assertSame(GoliDateCast::class, $model->getCasts()['scheduled_at'] ?? null);

        $model->addGoliDateCast('starts_at');

        $this->assertSame(GoliDateCast::class, $model->getCasts()['starts_at'] ?? null);
    }

    public function testSupportedInputsRoundTripThroughTheCast(): void
    {
        $cast = new GoliDateCast();
        $model = new GoliDateCastModel();

        $cases = [
            'gregorian-string' => '2024-03-20 10:15:00',
            'carbon-instance' => Carbon::create(2024, 3, 21, 0, 0, 0, 'Asia/Tehran'),
            'jalali-string' => '1403-01-02 08:30:45',
            'jalali-array' => [
                'year' => 1402,
                'month' => 12,
                'day' => 29,
                'hour' => 23,
                'minute' => 59,
                'second' => 59,
            ],
            'goli-instance' => Goli::parseGoli('1403-01-05 18:00:00'),
        ];

        foreach ($cases as $label => $input) {
            $expected = Goli::instance($input)->formatGregorian($model->getDateFormat());
            $stored = $cast->set($model, 'scheduled_at', $input, ['scheduled_at' => null]);

            $this->assertSame($expected, $stored, sprintf('Failed storing case "%s".', $label));

            $retrieved = $cast->get($model, 'scheduled_at', $stored, ['scheduled_at' => $stored]);

            $this->assertInstanceOf(Goli::class, $retrieved, sprintf('Failed retrieving case "%s".', $label));
            $this->assertSame(
                $expected,
                $retrieved->formatGregorian($model->getDateFormat()),
                sprintf('Failed round trip for case "%s".', $label),
            );
        }
    }

    public function testNullValuesRemainNull(): void
    {
        $cast = new GoliDateCast();
        $model = new GoliDateCastModel();

        $this->assertNull($cast->set($model, 'scheduled_at', null, []));
        $this->assertNull($cast->get($model, 'scheduled_at', null, []));
    }
}

class GoliDateCastModel extends Model
{
    use HasGoliDates;

    public $timestamps = false;

    protected $table = 'sample_events';

    /**
     * @var array<int, string>
     */
    protected array $goliDates = ['scheduled_at'];

    public function getDateFormat(): string
    {
        return 'Y-m-d H:i:s';
    }
}
