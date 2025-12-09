<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command("user:update-suspended")->everyMinute();
Schedule::command("recruitment:update-scheduled")->everyMinute();
Schedule::command("recruitment:update-completed")->everyMinute();
Schedule::command("interview:update-scheduled")->everyMinute();
