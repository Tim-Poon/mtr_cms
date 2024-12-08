from sensors.sensor import Sensor


class WIFI(Sensor):
    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self.active = False

    def deactivate(self):
        pass

    def activate(self, sensor, config):
        pass

    def get_sensors_list(self):
        return []

    def configure(self, config):
        pass
