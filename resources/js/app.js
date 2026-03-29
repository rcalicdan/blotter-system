import './confirmation';

function applyAppearance() {
    const appearance = localStorage.getItem('flux_appearance') || 'system';
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const isDark = appearance === 'dark' || (appearance === 'system' && prefersDark);
    document.documentElement.classList.toggle('dark', isDark);
}

document.addEventListener('livewire:navigating', applyAppearance);