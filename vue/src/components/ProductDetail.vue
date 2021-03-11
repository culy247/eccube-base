<template>
  <div>
    {{ $t('common_vue') }}
  </div>
</template>

<script>
import env from '../config'
import { EventSourcePolyfill } from 'event-source-polyfill';
export default {
  name: 'ProductDetail',
  data() {
    return {
      sseServer: null
    }
  },
  mounted() {
    this.initSse();
  },
  methods: {
    initSse() {
      let me = this;
        let lastEventId = this.getLastEventId();
        let now = (new Date).getTime();
        let hub = `${env.hub}?time=${now}`;
        let topics = [].concat((env.topics || '').split(','));
        topics.forEach( (topic) => hub += `&topic=${topic}` )

        if( lastEventId.length){
          hub += `&lastEventID=${lastEventId}`;
        }

        this.sseServer  = new EventSourcePolyfill(hub, {
          lastEventIdQueryParameterName: 'lastEventID',
          //withCredentials: true,
          headers: {
            Authorization: `Bearer ${env.mercure_token}`,
            'Request-Time': now,
            'Last-Event-ID': lastEventId
          }
        });

        this.sseServer.onopen = () => console.log('on open');

        this.sseServer.onerror = (err) => {
          console.log('error', err);
          me.sseServer.close();
          setTimeout( () => me.initSse() , 3000)
        };

        this.sseServer.onmessage = (event) => this.processEvent(event)

    },
    processEvent(event) {
      let lastEventId = event.lastEventId|| '';
      let eventId = event.id || '';
      this.setLastEventId(lastEventId);
      let eventType = event.type || '';

      let data = event.data || '';
      try {
        data = JSON.parse(data);
      }catch(e){}

      if( data && data.subscriber && data.type && data.type === 'Subscription') {
        this.processEventSubcrible(eventId, eventType, data, event);
      }
      else {
        this.processEventMessage(eventId, eventType, data, event);
      }

    },
    processEventSubcrible(is, type, data, event) {
      console.log( 'processEventSubcrible', is, type, data )
    },
    processEventMessage(is, type, data, event) {
      console.log( 'processEventMessage', is, type, data )
    },
    getLastEventId() {
      let eventId = localStorage.getItem('_aution_last_event_id');
      return eventId || ''
    },
    setLastEventId(eventId) {
      localStorage.setItem('_aution_last_event_id', eventId);
      return eventId || ''
    }
  },
  beforeDestroy() {
    try {
      this.sseServer.disconnect()

    }catch(e){}
  },
}
</script>
