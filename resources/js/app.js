import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import confetti from 'canvas-confetti';

if (import.meta.env.VITE_REVERB_APP_KEY) {
    window.Pusher = Pusher;

    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: import.meta.env.VITE_REVERB_APP_KEY,
        wsHost: import.meta.env.VITE_REVERB_HOST,
        wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
        wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
        forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
        enabledTransports: ['ws', 'wss'],
    });
}

const FINISH_SOUND_PATH = '/sounds/finish-trip.mp3';

function playFinishSound() {
    const audio = new Audio(FINISH_SOUND_PATH);
    audio.volume = 0.6;

    const playback = audio.play();

    if (playback && typeof playback.catch === 'function') {
        playback.catch(playSynthesisedChord);
    }
}

function playSynthesisedChord() {
    const Ctor = window.AudioContext || window.webkitAudioContext;

    if (!Ctor) {
        return;
    }

    try {
        const ctx = new Ctor();
        const start = ctx.currentTime;
        const notes = [523.25, 659.25, 783.99, 1046.5];

        notes.forEach((frequency, index) => {
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            const noteStart = start + index * 0.08;

            osc.type = 'sine';
            osc.frequency.value = frequency;
            gain.gain.setValueAtTime(0, noteStart);
            gain.gain.linearRampToValueAtTime(0.18, noteStart + 0.02);
            gain.gain.exponentialRampToValueAtTime(0.001, noteStart + 0.45);

            osc.connect(gain).connect(ctx.destination);
            osc.start(noteStart);
            osc.stop(noteStart + 0.45);
        });
    } catch (e) {
        // Audio is non-essential; silently swallow.
    }
}

function celebrateFinish() {
    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        confetti({
            particleCount: 110,
            spread: 75,
            startVelocity: 38,
            origin: { y: 0.7 },
            colors: ['#2f7d4f', '#f5c518', '#1a73e8', '#e53935', '#1a1a1a'],
        });
    }

    playFinishSound();
}

document.addEventListener('livewire:init', () => {
    window.Livewire.on('trip-finished', celebrateFinish);
});
