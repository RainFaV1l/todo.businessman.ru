<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Http\Requests\UpdateRequest;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class TaskController extends Controller
{
    /**
     * @return Factory|View|Application|\Illuminate\View\View
     */
    public function index() {

        $query = Task::query()->where('user_id', auth()->user()->getAuthIdentifier());

        if(request()->get('date'))
        {
            try
            {
                $date = request()->get('date');

                $formatDate = Carbon::parse($date)->format('Y-m-d');

                $tasks = $query->whereDate('created_at', '=', $formatDate)->latest()->get();
            }
            catch (\Exception $e)
            {
                return redirect('/');
            }
        }
        else
        {
            $tasks = $query->get();
        }

        return view('tasks.index', compact('tasks'));

    }

    /**
     * @param $name
     * @param $value
     * @return bool
     */
    public function strContains(string $name, string $value): bool
    {
        $string = mb_strtolower($name, 'UTF-8');

        return str_contains($string, $value);
    }

    /**
     * @param string $name
     * @return Carbon|false
     */
    public function checkContainsByDaysOfTheWeek(string $name): Carbon|false
    {
        $now = Carbon::now();

        if($this->strContains($name, 'сегодня')) return $now;
        if($this->strContains($name, 'послезавтра')) return $now->addDays(2);
        if($this->strContains($name, 'завтра')) return $now->addDay();
        if($this->strContains($name, 'позавчера')) return $now->subDays(2);
        if($this->strContains($name, 'вчера')) return $now->subDay();

        return false;
    }

    /**
     * @param StoreRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(StoreRequest $request): Application|RedirectResponse|Redirector
    {
        $data = $request->validated();

        $data['user_id'] = auth()->user()->getAuthIdentifier();

        $task = Task::query()->create($data);

        $date = $this->checkContainsByDaysOfTheWeek($task->name);

        $monthsMap = [
            'января' => 'Jan', 'январь' => 'Jan',
            'февраля' => 'Feb', 'февраль' => 'Feb',
            'марта' => 'Mar', 'март' => 'Mar',
            'апреля' => 'Apr', 'апрель' => 'Apr',
            'мая' => 'May', 'май' => 'May',
            'июня' => 'Jun', 'июнь' => 'Jun',
            'июля' => 'Jul', 'июль' => 'Jul',
            'августа' => 'Aug', 'август' => 'Aug',
            'сентября' => 'Sep', 'сентябрь' => 'Sep',
            'октября' => 'Oct', 'октябрь' => 'Oct',
            'ноября' => 'Nov', 'ноябрь' => 'Nov',
            'декабря' => 'Dec', 'декабрь' => 'Dec',
        ];

        if($date) {
            $task->update([
                'expired_at' => $date,
            ]);
        }
        else
        {
            $regex = '#(?:([1-9]|[12][0-9]|3[01])\s*(январ[ья]|феврал[ья]|март[а-я]|апрел[ья]|ма[йя]|июн[ья]|июл[ья]|август[а-я]|сентябр[ья]|октябр[ья]|ноябр[ья]|декабр[ья]))|(?:\b(\d{2}|\d{1})\.(0\d{1}|\d{1}|1[0-2]|[0-2])\.(\d{4}|\d{2})\b)#iu';

            preg_match($regex, $task->name, $matches);

            if(count($matches) === 3)
            {
                $day = $matches[1];

                $monthRus = $matches[2];

                $monthEng = $monthsMap[$monthRus];

                $year = Carbon::now()->format('Y');

                $date = Carbon::createFromFormat('d M Y', $day . ' ' . $monthEng . ' ' . $year)->format('Y-m-d');
            }
            else
            {
                if(mb_strlen($matches[5]) === 2)
                {
                    $date = Carbon::createFromFormat('d.m.y',$matches[3] . '.' . $matches[4] . '.' . $matches[5])->format('Y-m-d');
                } else
                {
                    $date = Carbon::parse($matches[0]);
                }
            }

            if($date)
            {
                $task->update([
                    'expired_at' => $date,
                ]);
            }


        }

        return redirect('/');
    }

    /**
     * @param Task $task
     * @return Application|RedirectResponse|Redirector
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect('/');
    }

    /**
     * @param Task $task
     * @return Application|RedirectResponse|Redirector
     */
    public function update(UpdateRequest $request, Task $task)
    {
        $data['user_id'] = auth()->user()->getAuthIdentifier();

        $data = $request->validated();

        $task->update($data);

        return redirect('/');
    }


    /**
     * @param Task $task
     * @return Application|RedirectResponse|Redirector
     */
    public function accept(Task $task)
    {
        $task->update([
            'status' => 'Выполнено'
        ]);

        return redirect('/');
    }

    /**
     * @param Task $task
     * @return Application|RedirectResponse|Redirector
     */
    public function reject(Task $task)
    {
        $task->update([
            'status' => 'Просрочено'
        ]);

        return redirect('/');
    }
}
