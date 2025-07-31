// Service Worker for Voice Cloning Website
// PWA functionality and offline caching

const CACHE_NAME = 'voice-cloning-v1.0.0';
const urlsToCache = [
    '/',
    '/index.php',
    '/assets/css/style.css',
    '/assets/js/script.js',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css',
    'https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700&display=swap'
];

// Install event - cache resources
self.addEventListener('install', event => {
    console.log('Service Worker: Installing...');
    
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Service Worker: Caching files');
                return cache.addAll(urlsToCache);
            })
            .then(() => {
                console.log('Service Worker: Installed successfully');
                return self.skipWaiting();
            })
            .catch(error => {
                console.error('Service Worker: Installation failed', error);
            })
    );
});

// Activate event - clean up old caches
self.addEventListener('activate', event => {
    console.log('Service Worker: Activating...');
    
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        console.log('Service Worker: Deleting old cache', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(() => {
            console.log('Service Worker: Activated successfully');
            return self.clients.claim();
        })
    );
});

// Fetch event - serve cached content when offline
self.addEventListener('fetch', event => {
    // Skip non-GET requests
    if (event.request.method !== 'GET') {
        return;
    }
    
    // Skip API requests for voice generation (they need to be online)
    if (event.request.url.includes('/api/generate_voice.php')) {
        return;
    }
    
    // Skip audio file uploads
    if (event.request.url.includes('/uploads/') || event.request.url.includes('/output/')) {
        return;
    }
    
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                // Return cached version if available
                if (response) {
                    console.log('Service Worker: Serving from cache', event.request.url);
                    return response;
                }
                
                // Otherwise fetch from network
                console.log('Service Worker: Fetching from network', event.request.url);
                return fetch(event.request)
                    .then(response => {
                        // Don't cache if not successful
                        if (!response || response.status !== 200 || response.type !== 'basic') {
                            return response;
                        }
                        
                        // Clone the response
                        const responseToCache = response.clone();
                        
                        // Add to cache for future use
                        caches.open(CACHE_NAME)
                            .then(cache => {
                                cache.put(event.request, responseToCache);
                            });
                        
                        return response;
                    })
                    .catch(error => {
                        console.error('Service Worker: Fetch failed', error);
                        
                        // Return offline page if available
                        if (event.request.destination === 'document') {
                            return caches.match('/');
                        }
                        
                        throw error;
                    });
            })
    );
});

// Background sync for failed voice generation requests
self.addEventListener('sync', event => {
    console.log('Service Worker: Background sync', event.tag);
    
    if (event.tag === 'voice-generation-retry') {
        event.waitUntil(
            retryFailedVoiceGenerations()
        );
    }
});

// Push notification support
self.addEventListener('push', event => {
    console.log('Service Worker: Push received');
    
    const options = {
        body: event.data ? event.data.text() : 'Voice generation completed!',
        icon: '/assets/images/icon-192x192.png',
        badge: '/assets/images/badge-72x72.png',
        vibrate: [100, 50, 100],
        data: {
            dateOfArrival: Date.now(),
            primaryKey: 1
        },
        actions: [
            {
                action: 'explore',
                title: 'View Result',
                icon: '/assets/images/checkmark.png'
            },
            {
                action: 'close',
                title: 'Close',
                icon: '/assets/images/xmark.png'
            }
        ]
    };
    
    event.waitUntil(
        self.registration.showNotification('Voice Cloning', options)
    );
});

// Notification click handler
self.addEventListener('notificationclick', event => {
    console.log('Service Worker: Notification clicked');
    
    event.notification.close();
    
    if (event.action === 'explore') {
        // Open the app
        event.waitUntil(
            clients.openWindow('/')
        );
    } else if (event.action === 'close') {
        // Just close the notification
        return;
    } else {
        // Default action - open the app
        event.waitUntil(
            clients.openWindow('/')
        );
    }
});

// Message handler for communication with main thread
self.addEventListener('message', event => {
    console.log('Service Worker: Message received', event.data);
    
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
    
    if (event.data && event.data.type === 'GET_VERSION') {
        event.ports[0].postMessage({ version: CACHE_NAME });
    }
    
    if (event.data && event.data.type === 'CACHE_AUDIO') {
        cacheAudioFile(event.data.url);
    }
});

// Utility functions
async function retryFailedVoiceGenerations() {
    try {
        // Get failed requests from IndexedDB or localStorage
        const failedRequests = await getFailedRequests();
        
        for (const request of failedRequests) {
            try {
                const response = await fetch('/api/generate_voice.php', {
                    method: 'POST',
                    body: request.formData
                });
                
                if (response.ok) {
                    // Request succeeded, remove from failed list
                    await removeFailedRequest(request.id);
                    
                    // Notify the client
                    const clients = await self.clients.matchAll();
                    clients.forEach(client => {
                        client.postMessage({
                            type: 'VOICE_GENERATION_RETRY_SUCCESS',
                            requestId: request.id
                        });
                    });
                }
            } catch (error) {
                console.error('Retry failed for request', request.id, error);
            }
        }
    } catch (error) {
        console.error('Background sync failed', error);
    }
}

async function cacheAudioFile(url) {
    try {
        const cache = await caches.open(CACHE_NAME);
        await cache.add(url);
        console.log('Service Worker: Audio file cached', url);
    } catch (error) {
        console.error('Service Worker: Failed to cache audio file', error);
    }
}

async function getFailedRequests() {
    // Implementation would depend on storage mechanism
    // This is a placeholder
    return [];
}

async function removeFailedRequest(id) {
    // Implementation would depend on storage mechanism
    // This is a placeholder
    console.log('Removing failed request', id);
}

// Error handler
self.addEventListener('error', event => {
    console.error('Service Worker error:', event.error);
});

// Unhandled rejection handler
self.addEventListener('unhandledrejection', event => {
    console.error('Service Worker unhandled rejection:', event.reason);
    event.preventDefault();
});

console.log('Service Worker: Script loaded successfully');