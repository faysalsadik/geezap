@extends('v2.layouts.app')
@section('content')
    <section class="py-20 bg-[#12122b]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="mb-8">
                <h2 class="text-2xl font-ubuntu-bold text-white">
                    Available Jobs <span class="text-pink-500" id="job-count">({{ $jobs->total() }})</span>
                </h2>
            </div>
            <livewire:job-filter />
        </div>
    </section>
@endsection
@push('extra-js')
    <script>
        const initialJobCount = document.getElementById('job-count').textContent.replace(/[()]/g, '');
        localStorage.setItem('last_job_count', initialJobCount);

        function updateJobCountDisplay(count) {
            const jobCountElement = document.getElementById('job-count');
            if (jobCountElement) {
                jobCountElement.textContent = `(${count})`;
            }
        }
        
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('jobCountUpdated', (count) => {
                localStorage.setItem('last_job_count', count);
                updateJobCountDisplay(count);
            });
        });
        
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                const lastCount = localStorage.getItem('last_job_count');
                if (lastCount) {
                    updateJobCountDisplay(lastCount);
                }
                
                const livewireComponent = Livewire.find(
                    document.querySelector('[wire\\:id]')?.getAttribute('wire:id')
                );
                
                if (livewireComponent) {
                    livewireComponent.call('render');
                }
            }
        });
        
        document.getElementById('jobs-filter-form')?.addEventListener('submit', function() {
            const checkboxes = document.querySelectorAll('.type-checkbox');
            const types = Array.from(checkboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value)
                .join(',');
            document.getElementById('types-hidden-input').value = types;
        });
    </script>
@endpush
