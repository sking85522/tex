// Tech Elevate X - Service Worker for Push Notifications
self.addEventListener('push', function(event) {
    console.log('[Service Worker] Push Received.');
    let data = { title: 'New Alert', body: 'You have a new notification.', url: '/' };

    if (event.data) {
        data = event.data.json();
    }

    const options = {
        body: data.body,
        icon: 'assets/img/icon.png', // Assuming a logo exists here
        badge: 'assets/img/badge.png',
        vibrate: [100, 50, 100],
        data: {
            dateOfArrival: Date.now(),
            primaryKey: '2',
            url: data.url
        },
        actions: [
            {action: 'explore', title: 'View Details', icon: 'assets/img/check.png'},
            {action: 'close', title: 'Close', icon: 'assets/img/xmark.png'},
        ]
    };

    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});

self.addEventListener('notificationclick', function(event) {
    console.log('[Service Worker] Notification click Received.');
    event.notification.close();

    if (event.action !== 'close') {
        event.waitUntil(
            clients.openWindow(event.notification.data.url)
        );
    }
});
