import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
  showTrace = false;
  timeSliderMax = 0;
  currMarkers = [];
  map = null;
  coordsList = [];

  static targets = ['timeSlider', 'currTime', 'maxTime']
  static values = {
    data: Array
  }

  initializeMap(center,  map_id = 'map') {
    this.map = L.map(map_id).setView(center, 10);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 20,
      attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(this.map);
  }

  addMarker(latLng) {
    return L.marker(latLng).addTo(this.map);
  }

  connectDots() {
    this.dataValue.forEach((participant) => {
      for (let [key, value] of Object.entries(participant.data)) {
        this.coordsList.push([value[0], value[1]]);
      }
    });

    L.polyline(this.coordsList).addTo(this.map);
  }

  loadMarkers(init = false) {
    this.dataValue.forEach((participant) => {
      let currTime = this.currTimeTarget.innerText;

      if (typeof participant.data[currTime] !== 'undefined') {
        let lat = participant.data[currTime][0];
        let long = participant.data[currTime][1];
        let fullName = participant.name + ' ' + participant.surname;
        let batteryVoltage = '';

        if (participant.data[currTime][2] !== null) {
          batteryVoltage =  parseFloat(participant.data[currTime][2]).toFixed(4);
        }

        if (init || this.showTrace) { //marker exists
          let marker = this.addMarker([lat, long]);
          marker.bindPopup(participant.deviceId + ' ' + fullName + ' ' + batteryVoltage);
          this.currMarkers.push({deviceId: participant.deviceId, marker: marker});
        }
        else { //add marker if it does not exist
          let currMarker = this.currMarkers.find(m => m.deviceId === participant.deviceId).marker;
          if (currMarker != null) {
            currMarker.setPopupContent(participant.deviceId + ' ' + fullName + ' ' + batteryVoltage);
            currMarker.setLatLng([lat, long]).update();

          }
        }
      }
    });
  }

  handleTimeSlider() {
    this.timeSliderTarget.addEventListener('input', (e) => {
      let endDate = new Date(this.maxTimeTarget.innerText);
      endDate.setSeconds(endDate.getSeconds() - this.timeSliderMax + parseInt(e.target.value));

      this.currTimeTarget.innerText = this.getFormattedDate(endDate);
      this.loadMarkers();
    });
  }

  connect() {
    this.timeSliderMax = this.timeSliderTarget.max;
    this.initializeMap([54.899845, 23.937298]);

    this.loadMarkers(true);
    this.handleTimeSlider();
    this.connectDots();
  }

  getFormattedDate(d){
    d = d.getFullYear() + "-"
      + ('0' + (d.getMonth() + 1)).slice(-2) + "-"
      + ('0' + d.getDate()).slice(-2) + " "
      + ('0' + d.getHours()).slice(-2) + ":"
      + ('0' + d.getMinutes()).slice(-2) + ":"
      + ('0' + d.getSeconds()).slice(-2);
    return d;
  }

}
