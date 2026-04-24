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

function isSoundEnabled() {
    try {
        const prefs = JSON.parse(localStorage.getItem('lista-prefs') || '{}');
        return prefs.soundEnabled !== false;
    } catch (e) {
        return true;
    }
}

/**
 * Play a sequence of notes with the Web Audio API.
 * Each note: { freq, time?, dur?, type?, gain? }
 */
function synth(notes) {
    if (!isSoundEnabled()) {
        return;
    }

    const Ctor = window.AudioContext || window.webkitAudioContext;

    if (!Ctor) {
        return;
    }

    try {
        const ctx = new Ctor();
        const start = ctx.currentTime;

        notes.forEach((note) => {
            const osc = ctx.createOscillator();
            const gainNode = ctx.createGain();
            const noteStart = start + (note.time ?? 0);
            const duration = note.dur ?? 0.4;
            const peakGain = note.gain ?? 0.18;

            osc.type = note.type ?? 'sine';
            osc.frequency.value = note.freq;
            gainNode.gain.setValueAtTime(0, noteStart);
            gainNode.gain.linearRampToValueAtTime(peakGain, noteStart + 0.02);
            gainNode.gain.exponentialRampToValueAtTime(0.001, noteStart + duration);

            osc.connect(gainNode).connect(ctx.destination);
            osc.start(noteStart);
            osc.stop(noteStart + duration);
        });
    } catch (e) {
        // Audio is non-essential; silently swallow.
    }
}

const sounds = {
    finish() {
        if (!isSoundEnabled()) {
            return;
        }

        const audio = new Audio(FINISH_SOUND_PATH);
        audio.volume = 0.6;

        const playback = audio.play();

        if (playback && typeof playback.catch === 'function') {
            playback.catch(() => {
                synth([
                    { freq: 523.25, time: 0 },
                    { freq: 659.25, time: 0.08 },
                    { freq: 783.99, time: 0.16 },
                    { freq: 1046.5, time: 0.24, dur: 0.5 },
                ]);
            });
        }
    },

    undo() {
        synth([
            { freq: 1046.5, time: 0 },
            { freq: 783.99, time: 0.07 },
            { freq: 659.25, time: 0.14 },
            { freq: 523.25, time: 0.21, dur: 0.45 },
        ]);
    },

    error() {
        synth([
            { freq: 415.3, time: 0, dur: 0.18, type: 'square', gain: 0.12 },
            { freq: 277.18, time: 0.12, dur: 0.3, type: 'square', gain: 0.12 },
        ]);
    },

    notify() {
        synth([
            { freq: 1318.51, time: 0, dur: 0.22, gain: 0.1 },
        ]);
    },
};

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

    sounds.finish();
}

document.addEventListener('livewire:init', () => {
    window.Livewire.on('trip-finished', celebrateFinish);
    window.Livewire.on('trip-restored', () => sounds.undo());
    window.Livewire.on('list-updated-remotely', () => sounds.notify());
    window.Livewire.on('validation-failed', () => sounds.error());
});
