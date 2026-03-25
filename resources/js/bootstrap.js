import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Define a stub for Echo to silence console warnings if no broadcaster is configured
window.Echo = window.Echo || {
    channel: () => ({ 
        listen: () => ({ stopListening: () => {} }), 
        stopListening: () => {} 
    }),
    private: () => ({ 
        listen: () => ({ stopListening: () => {} }), 
        stopListening: () => {} 
    }),
    join: () => ({ 
        here: () => ({ 
            joining: () => ({ 
                leaving: () => ({ 
                    listen: () => ({ stopListening: () => {} }) 
                }) 
            }) 
        }) 
    }),
    leave: () => {},
    leaveChannel: () => {},
    socketId: () => null,
    connector: { 
        options: {},
        socketId: () => null 
    },
};
