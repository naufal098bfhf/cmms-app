    <?php

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;

    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\Api\UserController;
    use App\Http\Controllers\Api\EquipmentController;
    use App\Http\Controllers\Admin\RiwayatTugasController;
    use App\Http\Controllers\Mekanik\TugasDaruratController;
    use App\Http\Controllers\Auth\LoginController;
    use App\Http\Controllers\MaintenancePlanning\ValidasiTugasController;
    use App\Http\Controllers\Api\NotifikasiController;

    use App\Models\TugasDarurat;
    use App\Models\TugasTetap;
    use App\Models\Equipment;

    // ======================================================
    // 🔐 LOGIN
    // ======================================================

    Route::post('/login', [AuthController::class, 'login']);


    // ======================================================
    // 🚀 DASHBOARD MEKANIK
    // ======================================================

    Route::get('/dashboard', function () {

        $jumlahEquipment = Equipment::count();

        $totalTugasTetap = TugasTetap::count();

        $totalTugasDarurat = TugasDarurat::count();

        $tugasSelesai =
            TugasTetap::where('status', 'selesai')->count() +
            TugasDarurat::where('status', 'selesai')->count();

        $tugasTetap = TugasTetap::latest()->get();

        $tugasDarurat = TugasDarurat::latest()->get();

        $tugas = [];

        foreach ($tugasTetap as $t) {

            $tugas[] = [
                'jenis' => 'Tugas Tetap',
                'pemberi_tugas' => $t->pemberi_tugas,
                'tgl_mulai' => $t->created_at->format('Y-m-d'),
                'tgl_selesai' => '-',
                'equipment' => $t->equipment,
                'lokasi' => $t->lokasi,
                'status' => $t->status,
            ];
        }

        foreach ($tugasDarurat as $t) {

            $tugas[] = [
                'jenis' => 'Tugas Darurat',
                'pemberi_tugas' => $t->pemberi_tugas,
                'tgl_mulai' => $t->tgl_mulai,
                'tgl_selesai' => $t->tgl_selesai,
                'equipment' => $t->equipment,
                'lokasi' => $t->lokasi,
                'status' => $t->status,
            ];
        }

        return response()->json([
            'jumlah_equipment' => $jumlahEquipment,
            'tugas_tetap' => $totalTugasTetap,
            'tugas_darurat' => $totalTugasDarurat,
            'tugas_selesai' => $tugasSelesai,
            'tugas' => $tugas,
        ]);
    });


    // ======================================================
    // 🚀 DASHBOARD ADMIN
    // ======================================================

    Route::get('/dashboard-admin', function () {

        $jumlahEquipment = Equipment::count();

        $totalTugasTetap = TugasTetap::count();

        $totalTugasDarurat = TugasDarurat::count();

        $tugasSelesai =
            TugasTetap::where('status', 'selesai')->count() +
            TugasDarurat::where('status', 'selesai')->count();

        $tugasTetap = TugasTetap::latest()->get();

        $tugasDarurat = TugasDarurat::latest()->get();

        $tugas = [];

        foreach ($tugasTetap as $t) {

            $tugas[] = [
                'equipment' => $t->equipment,
                'lokasi' => $t->lokasi,
                'status' => $t->status,
            ];
        }

        foreach ($tugasDarurat as $t) {

            $tugas[] = [
                'equipment' => $t->equipment,
                'lokasi' => $t->lokasi,
                'status' => $t->status,
            ];
        }

        return response()->json([
            'jumlahEquipment' => $jumlahEquipment,
            'tugasTetap' => $totalTugasTetap,
            'tugasDarurat' => $totalTugasDarurat,
            'tugasSelesai' => $tugasSelesai,
            'tugas' => $tugas,
        ]);
    });


    // ======================================================
    // 🔥 USERS CRUD
    // ======================================================

    Route::get('/users', [UserController::class, 'index']);

    Route::get('/users/{id}', [UserController::class, 'show']);

    Route::post('/users', [UserController::class, 'store']);

    Route::put('/users/{id}', [UserController::class, 'update']);

    Route::delete('/users/{id}', [UserController::class, 'destroy']);


    // ======================================================
    // 🔥 EQUIPMENT CRUD
    // ======================================================

    Route::get('/equipment', [EquipmentController::class, 'index']);

    Route::post('/equipment', [EquipmentController::class, 'store']);

    Route::get('/equipment/{id}', [EquipmentController::class, 'show']);

    Route::put('/equipment/{id}', [EquipmentController::class, 'update']);

    Route::delete('/equipment/{id}', [EquipmentController::class, 'destroy']);


    // ======================================================
    // 🔥 TUGAS TETAP
    // ======================================================

    Route::get('/tugas-tetap', function () {

        return TugasTetap::latest()->get();
    });

    Route::post('/tugas-tetap', function (Request $request) {

        $tugas = TugasTetap::create($request->all());

        \App\Models\Notifikasi::create([
    'user_id'   => $request->mekanik_id,
    'pesan'     => 'Tugas tetap baru diberikan kepada Anda',
    'jenis'     => 'tetap',
    'link'      => '/tugas-tetap',
    'tugas_id'  => $tugas->id,
    'read'      => false,
]);

        return response()->json([
            'success' => true,
            'data'    => $tugas
        ]);
    });

    Route::put('/tugas-tetap/{id}', function (Request $request, $id) {

        $tugas = TugasTetap::findOrFail($id);

        $tugas->update($request->all());

        return $tugas;
    });

    Route::delete('/tugas-tetap/{id}', function ($id) {

        TugasTetap::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Deleted'
        ]);
    });


    // ======================================================
    // 🔥 TUGAS DARURAT ADMIN
    // ======================================================
    Route::put('/tugas-darurat/{id}', function (Request $request, $id) {

    $tugas = TugasDarurat::findOrFail($id);

    $tugas->update([

        'tgl_mulai'     => $request->tgl_mulai,
        'tgl_selesai'   => $request->tgl_selesai,
        'mekanik_id'    => $request->mekanik_id,
        'nama_mekanik'  => $request->nama_mekanik,
        'equipment_id'  => $request->equipment_id,
        'equipment'     => $request->equipment,
        'tag_number'    => $request->tag_number,
        'eq_class'      => $request->eq_class,
        'bom'           => $request->bom,
        'task_list'     => $request->task_list,
        'lokasi'        => $request->lokasi,
        'status'        => $request->status,

    ]);

    return response()->json([
        'success' => true,
        'message' => 'Tugas berhasil diperbarui',
        'data'    => $tugas,
    ]);
});

    Route::post('/tugas-darurat', function (Request $request) {

        $tugas = TugasDarurat::create($request->all());

        \App\Models\Notifikasi::create([
    'user_id' => $request->mekanik_id,
    'pesan' => 'Tugas darurat baru diberikan kepada Anda',
    'jenis' => 'darurat',
    'link' => '/tugas-darurat',
    'tugas_id' => $tugas->id,
    'read' => false,
]);

        return response()->json([
            'success' => true,
            'data' => $tugas
        ]);
    });

    Route::delete('/tugas-darurat/{id}', function ($id) {

        TugasDarurat::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted'
        ]);
    });


    // ======================================================
    // 🔥 NOTIFIKASI
    // ======================================================

    Route::get('/notifikasi/{user_id}', function ($user_id) {

        return \App\Models\Notifikasi::where('user_id', $user_id)
            ->latest()
            ->get();
    });

    Route::put('/notifikasi/read/{id}', function ($id) {

        $notif = \App\Models\Notifikasi::findOrFail($id);

        $notif->update([
            'read' => true
        ]);

        return $notif;
    });
    Route::delete('/notifikasi/{id}', [NotifikasiController::class, 'destroy']);

    // ======================================================
    // 🔥 RIWAYAT TUGAS
    // ======================================================

    Route::get('/riwayat-tugas', [RiwayatTugasController::class, 'indexApi']);

        Route::get('/admin/tugas-darurat', function () {

            return TugasDarurat::latest()->get();
        });

    // ==================================================
    // MEKANIK
    // ==================================================

    Route::middleware('auth:sanctum')->group(function () {

        Route::get(
            '/mekanik/tugas-darurat',
            [TugasDaruratController::class, 'apiIndex']
        );

        Route::put(
            '/mekanik/tugas-darurat/{id}/status',
            [TugasDaruratController::class, 'apiUpdateStatus']
        );

        Route::post(
            '/mekanik/tugas-darurat/{id}/upload',
            [TugasDaruratController::class, 'apiUploadFoto']
        );
    });

    // ======================================================
    // 🔥 GET TUGAS MEKANIK BERDASARKAN ID
    // ======================================================

    Route::get('/mekanik/tugas-darurat/{id}', function ($id) {

        return TugasDarurat::where('mekanik_id', $id)
            ->whereDate('tgl_mulai', '<=', now())
            ->latest()
            ->get();
    });

    Route::post(
        '/login',
        [LoginController::class, 'apiLogin']
    );

    Route::middleware('auth:sanctum')->group(function () {

        Route::get(
            '/mekanik/tugas-tetap',
            [\App\Http\Controllers\Mekanik\TugasTetapController::class,
            'apiIndex']
        );

        Route::put(
            '/mekanik/tugas-tetap/{id}/status',
            [\App\Http\Controllers\Mekanik\TugasTetapController::class,
            'apiUpdateStatus']
        );

        Route::post(
            '/mekanik/tugas-tetap/{id}/upload',
            [\App\Http\Controllers\Mekanik\TugasTetapController::class,
            'apiUploadFoto']
        );
    });


    Route::middleware('auth:sanctum')->group(function () {

        // LIST VALIDASI
        Route::get(
            '/maintenance-planning/validasi',
            [ValidasiTugasController::class, 'getValidasi']
        );

        // VALIDASI TUGAS TETAP
        Route::post(
            '/maintenance-planning/validasi/tetap/{id}',
            [ValidasiTugasController::class, 'validasiTetapApi']
        );

        // VALIDASI TUGAS DARURAT
        Route::post(
            '/maintenance-planning/validasi/darurat/{id}',
            [ValidasiTugasController::class, 'validasiDaruratApi']
        );

        Route::get(
        '/mp/validasi',
        [ValidasiTugasController::class,'index']
    );

    Route::put(
        '/mp/validasi/tetap/{id}',
        [ValidasiTugasController::class,'validasi']
    );

    Route::put(
        '/mp/validasi/darurat/{id}',
        [ValidasiTugasController::class,'validasiDarurat']
    );
    });

