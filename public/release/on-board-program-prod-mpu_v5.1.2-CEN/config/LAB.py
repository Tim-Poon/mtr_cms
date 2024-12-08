# on-board-program-from-pi version of different sites
from config.common import SOURCE_INFO
from enum import Enum
import os

MAPS = {
    11:[[12705533.454438854,2483584.18816789,1],[12705533.234537795,2483575.9300001203,1],[12705530.331842283,2483576.011361375,1],[12705530.287862247,2483584.025445371,1]],
    12:[[12705532.3109529,2483586.2628792073,1],[12705532.442893602,2483584.1474872245,1],[12705517.709515275,2483584.4729321357,1],[12705518.149317687,2483585.9781148466,1]],
    13:[[12705532.398913553,2483593.9921937142,1],[12705532.486874063,2483586.4256016226,1],[12705529.936020462,2483586.1408370608,1],[12705530.067961156,2483593.936823757,1]],
    14:[[12705531.079506567,2483595.2792832297,1],[12705531.343387958,2483592.960489454,1],[12705530.111941345,2483593.326614868,1],[12705529.89204028,2483595.2386026843,1]],
    15:[[12705525.537996914,2483595.035199934,1],[12705525.588564396,2483575.2149595823,1],[12705524.533038812,2483575.2149595823,1],[12705524.137216568,2483595.0671002134,1]],
    16:[[12705519.387351278,2483595.43322551,1],[12705519.827153705,2483575.2149595823,1],[12705518.155904813,2483575.2963208454,1],[12705517.628141878,2483595.3111837856,1]],
}

# [data_pool]
# maximum time range inside a current data pool
window_size_in_sec = 2.
# time gap between two updates in data pool(second)
sec_per_update = 1

# [TRACKING]
CAL_PERIOD_SEC = 1.3  # time gap between two calculations (second)

ALARM_PARAMS = {
    'ALARM_PIN': 17,

    # rule 1: alarm |= within(pos, ALARM_ZONE) and recent(vel_history) > ALARM_VELOCITY_THRESHOLD_HIGHER
    'ALARM_VELOCITY_THRESHOLD_HIGHER': 1.5,
    'ALARM_TOLERANCE_NUMBER': 5,

    # rule 2: alarm |= within(pos, ALARM_ZONE) and step > STEP_ALARM_THRESHOLD_HIGHER
    'STEP_ALARM_THRESHOLD_HIGHER': 10,

    # rule 3: alarm |= within(pos, ALARM_ZONE) and step > STEP_ALARM_THRESHOLD_LOWER and abs(vel) >ALARM_VELOCITY_THRESHOLD_LOWER
    'ALARM_VELOCITY_THRESHOLD_LOWER': 1.5,
    'STEP_ALARM_THRESHOLD_LOWER': 10,

    'ALARM_ZONE': [polygon for polyid, polygon in MAPS.items()],

}

FILTER_MODULE_PARAMS = {
    'RSSI_FILTER_ALGO_FILE': "avg_filter.py",
    'RSSI_THRESHOLD': -80,
}

LOCALIZATION_MODULE_PARAMS = {
    'LOCALIZATION_ALGO_FILE': "new_localization_pf.cpython-37m-arm-linux-gnueabihf.so",
    'num_of_particles': 500,
    'max_vel': 2.,  # maximum speed of human, unit in m/s
    'obs_model_mean': [-76.0656, 2.8871],  # mean and variance of P(rssi, distance)
    'obs_model_var': [[53.171, -5.218], [-5.218, 2.912]],
    'loss_model_range': [-120, -110],
    'weight_observe': 0.8,  # mean and variance of P(rssi, distance)
    'weight_loss': 1 - 0.8,
    # 'map_bound': [[-70, 110], [0, 15]],  # todo: can be calculated from MAPS directly
    # 'position_candidates_regions': [MAPS[1], MAPS[2], MAPS[3], MAPS[4], MAPS[5], MAPS[6]],
    'grid_size': 2.,
}

VELOCITY_MODULE_PARAMS = {
    'VELOCITY_ALGO_FILE': "analyse_velocity.py",
    'VELOCITY_LEAST_SQUARE_WINDOW': 15,  # maximum num of position used to calculate velocity via least square
    'LOCALIZATION_CAL_PERIOD': CAL_PERIOD_SEC,
}


SOURCE_INFO_DICT = {
    '3142110001': SOURCE_INFO(source_identifier='3142110001', x=12705517.885436, y=2483595.5380563, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '3142110002': SOURCE_INFO(source_identifier='3142110002', x=12705517.753496, y=2483587.6867006, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '3142120001': SOURCE_INFO(source_identifier='3142120001', x=12705517.225732, y=2483585.6119894, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '3142110003': SOURCE_INFO(source_identifier='3142110003', x=12705517.797475, y=2483581.0150793, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '3142110004': SOURCE_INFO(source_identifier='3142110004', x=12705519.160863, y=2483575.035026, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '3142110005': SOURCE_INFO(source_identifier='3142110005', x=12705525.186155, y=2483574.9943454, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '3142110006': SOURCE_INFO(source_identifier='3142110006', x=12705531.211446, y=2483576.0520421, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '3142110007': SOURCE_INFO(source_identifier='3142110007', x=12705533.278518, y=2483580.282828, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '3142110008': SOURCE_INFO(source_identifier='3142110008', x=12705532.706775, y=2483585.5713089, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '3142110009': SOURCE_INFO(source_identifier='3142110009', x=12705534.070162, y=2483590.3716206, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '3142110010': SOURCE_INFO(source_identifier='3142110010', x=12705530.199902, y=2483595.5380563, z=1, type='non-lon-lat', activated=True, addition_info={}),
    '3142110011': SOURCE_INFO(source_identifier='3142110011', x=12705524.658393, y=2483595.5380563, z=1, type='non-lon-lat', activated=True, addition_info={}),
}

if __name__ == "__main__":
    # test for configs
    pass

