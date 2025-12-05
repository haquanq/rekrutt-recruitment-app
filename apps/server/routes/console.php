<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command("recruitment:process-sheduled")->everyMinute();
