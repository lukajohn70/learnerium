<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learnerium Database Updater</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-2xl space-y-6">
        <div class="flex items-center gap-4 border-b border-slate-800 pb-6">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center {{ $status === 'success' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400' }}">
                <i class="fas {{ $status === 'success' ? 'fa-database text-2xl' : 'fa-exclamation-triangle text-2xl' }}"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-white">Learnerium Database Updater</h1>
                <p class="text-sm text-slate-400">Automated Schema Sync & Migration Tool</p>
            </div>
        </div>

        <div class="space-y-2">
            <div class="flex items-center justify-between text-xs uppercase font-bold tracking-wider text-slate-400">
                <span>Execution Status</span>
                <span class="{{ $status === 'success' ? 'text-emerald-400' : 'text-rose-400' }}">
                    <i class="fas {{ $status === 'success' ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                    {{ strtoupper($status) }}
                </span>
            </div>
            <div class="bg-slate-950 border border-slate-800/80 rounded-2xl p-4 font-mono text-xs text-slate-300 overflow-x-auto whitespace-pre-wrap leading-relaxed max-h-96">
{{ trim($output) ?: 'No pending migrations. Database is already up to date!' }}
            </div>
        </div>

        <div class="pt-2 flex flex-col sm:flex-row gap-3">
            <a href="{{ route('home') }}" class="flex-1 text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-6 rounded-2xl transition shadow-lg shadow-indigo-600/20 text-sm">
                <i class="fas fa-home mr-2"></i>Go to Homepage
            </a>
            <a href="{{ route('admin.dashboard') }}" class="flex-1 text-center bg-slate-800 hover:bg-slate-700 text-white font-bold py-3.5 px-6 rounded-2xl transition text-sm">
                <i class="fas fa-shield-alt mr-2"></i>Admin Dashboard
            </a>
        </div>

        <p class="text-center text-xs text-slate-500 pt-2">
            &copy; {{ date('Y') }} Learnerium Inc. &bull; Powered by JLM
        </p>
    </div>
</body>
</html>
