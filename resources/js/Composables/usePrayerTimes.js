import { zones as malaysiaZones } from '@/Utils/zones';
import { computed, ref } from 'vue';

// Convert zones from zones.js to the format needed for the dropdown
const zones = ref(
    malaysiaZones.map((zone) => ({
        code: zone.jakimCode,
        name: `${zone.negeri} - ${zone.daerah}`,
    })),
);

// Default zone
const defaultZone = ref('SGR01');

// Dummy data for fallback
const dummyPrayerTimes = {
    SGR01: {
        date: new Date().toISOString().split('T')[0],
        subuh: '05:42',
        syuruk: '07:00',
        zohor: '13:15',
        asar: '16:45',
        maghrib: '19:21',
        isyak: '20:45',
    },
    KUL01: {
        date: new Date().toISOString().split('T')[0],
        subuh: '05:43',
        syuruk: '07:01',
        zohor: '13:16',
        asar: '16:46',
        maghrib: '19:22',
        isyak: '20:45',
    },
    // Add more fallback data for other zones
    JHR01: {
        date: new Date().toISOString().split('T')[0],
        subuh: '05:45',
        syuruk: '07:03',
        zohor: '13:17',
        asar: '16:44',
        maghrib: '19:20',
        isyak: '20:40',
    },
    PHG01: {
        date: new Date().toISOString().split('T')[0],
        subuh: '05:41',
        syuruk: '06:59',
        zohor: '13:14',
        asar: '16:43',
        maghrib: '19:19',
        isyak: '20:39',
    },
};

export function usePrayerTimes() {
    const prayerTimes = ref(null);
    const loading = ref(true);
    const error = ref(null);
    const selectedZone = ref(
        localStorage.getItem('selectedZone') || defaultZone.value,
    );
    const refreshInterval = ref(null);
    const midnightRefreshTimeout = ref(null);
    const countdownInterval = ref(null);
    const countdownDisplay = ref(''); // Use a separate ref for the display text

    // Format the date to display in a user-friendly way
    const formatDate = (date) => {
        if (!date) return '';
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        };
        return new Date(date).toLocaleDateString('ms-MY', options);
    };

    // Use fallback data when API fails
    const useFallbackData = () => {
        const fallbackData =
            dummyPrayerTimes[selectedZone.value] || dummyPrayerTimes.SGR01;
        prayerTimes.value = {
            date: fallbackData.date,
            times: {
                subuh: fallbackData.subuh,
                syuruk: fallbackData.syuruk,
                zohor: fallbackData.zohor,
                asar: fallbackData.asar,
                maghrib: fallbackData.maghrib,
                isyak: fallbackData.isyak,
            },
        };
        loading.value = false;
        error.value = 'Using fallback data due to API error';
    };

    // Fetch prayer times from the API
    const fetchPrayerTimes = async () => {
        loading.value = true;
        error.value = null;

        try {
            // Get the base URL from the current window location
            const baseUrl = window.location.origin;

            // Use the API route for prayer times
            const apiUrl = `${baseUrl}/api/prayer-times/${selectedZone.value}`;

            const response = await fetch(apiUrl, {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!response.ok) {
                const errorText = await response.text();
                console.error('API error response:', errorText);
                throw new Error(`API error: ${response.status}`);
            }

            const data = await response.json();

            if (data.prayerTime && data.prayerTime.length > 0) {
                const todayData = data.prayerTime[0];
                prayerTimes.value = {
                    date: todayData.date,
                    times: {
                        subuh: todayData.fajr,
                        syuruk: todayData.syuruk,
                        zohor: todayData.dhuhr,
                        asar: todayData.asr,
                        maghrib: todayData.maghrib,
                        isyak: todayData.isha,
                    },
                };
            } else {
                throw new Error('No prayer times data available');
            }
        } catch (err) {
            console.error('API request failed, using fallback data:', err);
            error.value = err.message;
            useFallbackData();
        } finally {
            loading.value = false;

            // After fetching prayer times, update the countdown immediately
            if (countdownInterval.value) {
                updateCountdown();
            }
        }
    };

    // Change the selected zone and fetch new prayer times
    const changeZone = async (zoneCode) => {
        // Check if zoneCode is an event object (from v-model change event)
        if (zoneCode && zoneCode.target && zoneCode.target.value) {
            zoneCode = zoneCode.target.value;
        }

        selectedZone.value = zoneCode;
        localStorage.setItem('selectedZone', zoneCode);
        await fetchPrayerTimes();
    };

    // Calculate the next prayer time
    const nextPrayer = computed(() => {
        if (!prayerTimes.value) return null;

        const now = new Date();
        const currentHour = now.getHours();
        const currentMinute = now.getMinutes();
        const currentTime24 = `${currentHour.toString().padStart(2, '0')}:${currentMinute.toString().padStart(2, '0')}`;

        const prayers = [
            { name: 'Subuh', time: prayerTimes.value.times.subuh },
            { name: 'Syuruk', time: prayerTimes.value.times.syuruk },
            { name: 'Zohor', time: prayerTimes.value.times.zohor },
            { name: 'Asar', time: prayerTimes.value.times.asar },
            { name: 'Maghrib', time: prayerTimes.value.times.maghrib },
            { name: 'Isyak', time: prayerTimes.value.times.isyak },
        ];

        // Find the next prayer
        for (const prayer of prayers) {
            if (prayer.time > currentTime24) {
                return prayer;
            }
        }

        // If all prayers for today have passed, return the first prayer for tomorrow
        return {
            name: 'Subuh',
            time: prayerTimes.value.times.subuh,
        };
    });

    // Update countdown display without affecting other components
    const updateCountdown = () => {
        if (!nextPrayer.value) {
            countdownDisplay.value = '';
            return;
        }

        const now = new Date();
        const [nextHour, nextMinute] = nextPrayer.value.time
            .split(':')
            .map(Number);

        const nextPrayerTime = new Date(now);
        nextPrayerTime.setHours(nextHour, nextMinute, 0);

        // If it's for tomorrow, add a day
        if (nextPrayer.value.name.includes('Subuh')) {
            nextPrayerTime.setDate(nextPrayerTime.getDate() + 1);
        }

        const diffMs = nextPrayerTime - now;

        // If the countdown has finished or is negative, refresh prayer times to move to the next prayer
        if (diffMs <= 0) {
            fetchPrayerTimes();
            return;
        }

        const diffHrs = Math.floor(diffMs / (1000 * 60 * 60));
        const diffMins = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
        const diffSecs = Math.floor((diffMs % (1000 * 60)) / 1000);

        countdownDisplay.value = `${diffHrs}h ${diffMins}m ${diffSecs}s`;
    };

    // Start the countdown timer that updates only the countdown display
    const startCountdownTimer = () => {
        // Clear any existing interval
        if (countdownInterval.value) {
            clearInterval(countdownInterval.value);
        }

        // Initial update
        updateCountdown();

        // Update the countdown display every second
        countdownInterval.value = setInterval(() => {
            updateCountdown();
        }, 1000);
    };

    // Set up auto-refresh for prayer times
    const setupAutoRefresh = () => {
        // Clear any existing intervals
        if (refreshInterval.value) {
            clearInterval(refreshInterval.value);
        }

        // Refresh every 30 minutes
        refreshInterval.value = setInterval(fetchPrayerTimes, 30 * 60 * 1000);

        const setupMidnightRefresh = () => {
            // Clear any existing timeout
            if (midnightRefreshTimeout.value) {
                clearTimeout(midnightRefreshTimeout.value);
            }

            // Calculate time until midnight
            const now = new Date();
            const midnight = new Date(now);
            midnight.setHours(24, 0, 0, 0);
            const timeUntilMidnight = midnight - now;

            // Set timeout to refresh at midnight
            midnightRefreshTimeout.value = setTimeout(() => {
                fetchPrayerTimes();
                setupMidnightRefresh(); // Set up for the next day
            }, timeUntilMidnight);
        };

        setupMidnightRefresh();

        // Start the countdown timer
        startCountdownTimer();
    };

    // Cleanup function to clear intervals and timeouts
    const cleanup = () => {
        if (refreshInterval.value) {
            clearInterval(refreshInterval.value);
            refreshInterval.value = null;
        }

        if (midnightRefreshTimeout.value) {
            clearTimeout(midnightRefreshTimeout.value);
            midnightRefreshTimeout.value = null;
        }

        if (countdownInterval.value) {
            clearInterval(countdownInterval.value);
            countdownInterval.value = null;
        }
    };

    return {
        zones,
        prayerTimes,
        loading,
        error,
        selectedZone,
        formatDate,
        fetchPrayerTimes,
        changeZone,
        setupAutoRefresh,
        cleanup,
        nextPrayer,
        countdownDisplay, // Return the countdown display instead of computed property
    };
}
