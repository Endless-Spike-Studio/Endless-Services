<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
	protected  string $name = 'endless_proxy';

	public function up(): void
	{
		DB::unprepared("CREATE SCHEMA $this->name");
	}

	public function down(): void
	{
		DB::unprepared("DROP SCHEMA IF EXISTS $this->name");
	}
};