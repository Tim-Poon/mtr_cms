# on-board-program-from-pi version of different sites
from collections import namedtuple
from enum import Enum
from config.common import SOURCE_INFO
import os

MAPS = {
11:[[12695203.043767372,2481074.3512394964,1],[12695205.511970801,2481074.721802387,1],[12695190.803289186,2481146.477913535,1],[12695188.283351898,2481145.9830444474,1]],
12:[[12695177.192622023,2481095.008423712,1],[12695176.49643613,2481098.3218130535,1],[12695199.928119395,2481103.367318641,1],[12695200.61547881,2481099.840314059,1]],
13:[[12695192.354551729,2481089.9195613875,1],[12695180.44735867,2481144.3889711294,1],[12695183.21793107,2481144.9851204245,1],[12695194.540696837,2481090.3346790713,1]],
14:[[12695193.329345467,2481101.631540824,1],[12695196.308154037,2481102.24732955,1],[12695192.345865615,2481121.3261027182,1],[12695189.400732918,2481120.7629027287,1]],
15:[[12695188.799966387,2481116.2842990337,1],[12695187.90925096,2481120.486826249,1],[12695193.50860005,2481121.6865186216,1],[12695194.37043911,2481117.4301658645,1]],
16:[[12695190.223017702,2481109.5927771647,1],[12695189.052399639,2481115.0366481617,1],[12695194.7340102,2481116.1467371015,1],[12695195.861940803,2481110.742418267,1]],
17:[[12695191.656410597,2481102.8801525976,1],[12695190.495714106,2481108.37447102,1],[12695196.016087342,2481109.5067190337,1],[12695197.200157069,2481104.002916357,1]],
18:[[12695184.283807755,2481137.722146657,1],[12695183.443022858,2481141.7623348054,1],[12695188.956132278,2481142.8881603945,1],[12695189.739545807,2481138.8480592896,1]],
19:[[12695183.235095745,2481142.968692005,1],[12695182.88480767,2481144.7156581446,1],[12695188.441708378,2481145.8797213947,1],[12695188.8178054,2481144.152065867,1]],
110:[[12695185.199308183,2481141.437778898,1],[12695184.66165772,2481143.8810023563,1],[12695187.560165277,2481144.424704106,1],[12695188.013270335,2481141.9213233287,1]],
111:[[12695186.916948086,2481109.2961992323,1],[12695188.244919868,2481109.5653216215,1],[12695181.491590388,2481140.693299048,1],[12695180.185969878,2481140.4525658167,1]],
}

SOURCE_INFO_DICT = {
'1002110002': SOURCE_INFO(source_identifier='1002110002', x=12695202.539909, y=2481076.5756749, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110004': SOURCE_INFO(source_identifier='1002110004', x=12695201.064938, y=2481083.7768369, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110005': SOURCE_INFO(source_identifier='1002110005', x=12695203.027176, y=2481087.5117115, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110006': SOURCE_INFO(source_identifier='1002110006', x=12695199.776325, y=2481089.9383363, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110007': SOURCE_INFO(source_identifier='1002110007', x=12695201.818951, y=2481093.5036464, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110008': SOURCE_INFO(source_identifier='1002110008', x=12695198.890638, y=2481093.8571715, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110009': SOURCE_INFO(source_identifier='1002110009', x=12695200.515612, y=2481099.6060691, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110010': SOURCE_INFO(source_identifier='1002110010', x=12695197.772837, y=2481098.9759416, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110001': SOURCE_INFO(source_identifier='1002110001', x=12695205.569721, y=2481075.2010839, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110003': SOURCE_INFO(source_identifier='1002110003', x=12695204.330562, y=2481081.370478, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110011': SOURCE_INFO(source_identifier='1002110011', x=12695200.488348, y=2481099.9025028, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110013': SOURCE_INFO(source_identifier='1002110013', x=12695198.556967, y=2481109.1558282, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110014': SOURCE_INFO(source_identifier='1002110014', x=12695198.478294, y=2481109.4064346, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110015': SOURCE_INFO(source_identifier='1002110015', x=12695197.551075, y=2481114.4384943, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110016': SOURCE_INFO(source_identifier='1002110016', x=12695196.494382, y=2481119.4215404, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110017': SOURCE_INFO(source_identifier='1002110017', x=12695195.458772, y=2481124.4648734, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110019': SOURCE_INFO(source_identifier='1002110019', x=12695193.378707, y=2481134.4884073, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110020': SOURCE_INFO(source_identifier='1002110020', x=12695192.39507, y=2481139.2936957, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110021': SOURCE_INFO(source_identifier='1002110021', x=12695189.75839, y=2481146.2349467, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110022': SOURCE_INFO(source_identifier='1002110022', x=12695185.912329, y=2481145.4711229, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110023': SOURCE_INFO(source_identifier='1002110023', x=12695185.641439, y=2481145.3983415, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110024': SOURCE_INFO(source_identifier='1002110024', x=12695181.855264, y=2481144.5971495, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110025': SOURCE_INFO(source_identifier='1002110025', x=12695181.050593, y=2481140.9402422, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110026': SOURCE_INFO(source_identifier='1002110026', x=12695179.273902, y=2481136.8901368, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110028': SOURCE_INFO(source_identifier='1002110028', x=12695181.328244, y=2481127.6081057, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110029': SOURCE_INFO(source_identifier='1002110029', x=12695182.816768, y=2481120.8906514, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110030': SOURCE_INFO(source_identifier='1002110030', x=12695183.37938, y=2481117.9377391, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110031': SOURCE_INFO(source_identifier='1002110031', x=12695184.12982, y=2481114.3661389, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110032': SOURCE_INFO(source_identifier='1002110032', x=12695184.973112, y=2481110.555476, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110033': SOURCE_INFO(source_identifier='1002110033', x=12695188.246789, y=2481108.9833628, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110034': SOURCE_INFO(source_identifier='1002110034', x=12695188.216278, y=2481108.7670661, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110036': SOURCE_INFO(source_identifier='1002110036', x=12695189.617184, y=2481102.099666, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110037': SOURCE_INFO(source_identifier='1002110037', x=12695177.15288, y=2481094.95564, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110038': SOURCE_INFO(source_identifier='1002110038', x=12695180.833275, y=2481095.7469988, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110039': SOURCE_INFO(source_identifier='1002110039', x=12695187.950254, y=2481097.1607968, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110041': SOURCE_INFO(source_identifier='1002110041', x=12695191.06144, y=2481095.1614657, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110040': SOURCE_INFO(source_identifier='1002110040', x=12695190.514199, y=2481096.6515804, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110042': SOURCE_INFO(source_identifier='1002110042', x=12695192.0806, y=2481090.4347561, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110044': SOURCE_INFO(source_identifier='1002110044', x=12695197.626796, y=2481088.5700023, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110045': SOURCE_INFO(source_identifier='1002110045', x=12695194.808221, y=2481093.0694052, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110046': SOURCE_INFO(source_identifier='1002110046', x=12695193.727395, y=2481098.1792438, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1002110047': SOURCE_INFO(source_identifier='1002110047', x=12695192.739638, y=2481101.8657695, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110048': SOURCE_INFO(source_identifier='1002110048', x=12695192.116427, y=2481102.355413, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1002110049': SOURCE_INFO(source_identifier='1002110049', x=12695192.500861, y=2481103.0021888, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110050': SOURCE_INFO(source_identifier='1002110050', x=12695193.09413, y=2481102.5312294, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1002110051': SOURCE_INFO(source_identifier='1002110051', x=12695191.363743, y=2481108.5662763, z=1, type='non-lon-lat', activated=False, addition_info={}),
#'1002110053': SOURCE_INFO(source_identifier='1002110053', x=12695191.074828, y=2481109.6936787, z=1, type='non-lon-lat', activated=False, addition_info={}),
'1002110054': SOURCE_INFO(source_identifier='1002110054', x=12695191.630974, y=2481109.6135959, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1002110055': SOURCE_INFO(source_identifier='1002110055', x=12695189.96615, y=2481115.2651847, z=1, type='non-lon-lat', activated=False, addition_info={}),
#'1002110057': SOURCE_INFO(source_identifier='1002110057', x=12695189.767723, y=2481116.367997, z=1, type='non-lon-lat', activated=False, addition_info={}),
'1002110056': SOURCE_INFO(source_identifier='1002110056', x=12695189.407697, y=2481115.6942674, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1002110059': SOURCE_INFO(source_identifier='1002110059', x=12695188.496099, y=2481121.9343155, z=1, type='non-lon-lat', activated=False, addition_info={}),
'1002110058': SOURCE_INFO(source_identifier='1002110058', x=12695190.320693, y=2481115.9052749, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110061': SOURCE_INFO(source_identifier='1002110061', x=12695187.974726, y=2481125.9508836, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110062': SOURCE_INFO(source_identifier='1002110062', x=12695186.714388, y=2481128.6053119, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110063': SOURCE_INFO(source_identifier='1002110063', x=12695185.086914, y=2481136.2606375, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110064': SOURCE_INFO(source_identifier='1002110064', x=12695183.854976, y=2481142.5331532, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110065': SOURCE_INFO(source_identifier='1002110065', x=12695184.459428, y=2481142.014577, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110066': SOURCE_INFO(source_identifier='1002110066', x=12695187.846329, y=2481143.3956543, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110067': SOURCE_INFO(source_identifier='1002110067', x=12695188.375395, y=2481142.8635215, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110069': SOURCE_INFO(source_identifier='1002110069', x=12695190.147269, y=2481136.7887976, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110070': SOURCE_INFO(source_identifier='1002110070', x=12695191.381224, y=2481130.5874871, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110071': SOURCE_INFO(source_identifier='1002110071', x=12695191.493335, y=2481126.5786271, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110072': SOURCE_INFO(source_identifier='1002110072', x=12695192.920578, y=2481123.3852812, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1002110073': SOURCE_INFO(source_identifier='1002110073', x=12695192.5806, y=2481122.7692492, z=1, type='non-lon-lat', activated=False, addition_info={}),
#'1002110074': SOURCE_INFO(source_identifier='1002110074', x=12695193.722891, y=2481117.2085736, z=1, type='non-lon-lat', activated=False, addition_info={}),
'1002110075': SOURCE_INFO(source_identifier='1002110075', x=12695194.221944, y=2481116.6951686, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1002110076': SOURCE_INFO(source_identifier='1002110076', x=12695193.932223, y=2481116.0881741, z=1, type='non-lon-lat', activated=False, addition_info={}),
#'1002110078': SOURCE_INFO(source_identifier='1002110078', x=12695195.059745, y=2481110.4665292, z=1, type='non-lon-lat', activated=False, addition_info={}),
'1002110079': SOURCE_INFO(source_identifier='1002110079', x=12695195.674674, y=2481110.0222133, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1002110080': SOURCE_INFO(source_identifier='1002110080', x=12695195.309567, y=2481109.4016618, z=1, type='non-lon-lat', activated=False, addition_info={}),
'1002110081': SOURCE_INFO(source_identifier='1002110081', x=12695194.74978, y=2481109.8317679, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1002110082': SOURCE_INFO(source_identifier='1002110082', x=12695196.449775, y=2481103.8023139, z=1, type='non-lon-lat', activated=False, addition_info={}),
#'1002110084': SOURCE_INFO(source_identifier='1002110084', x=12695196.689121, y=2481102.6631847, z=1, type='non-lon-lat', activated=False, addition_info={}),
'1002110086': SOURCE_INFO(source_identifier='1002110086', x=12695184.940285, y=2481096.5214134, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110012': SOURCE_INFO(source_identifier='1002110012', x=12695201.909746, y=2481106.076261, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110018': SOURCE_INFO(source_identifier='1002110018', x=12695194.325418, y=2481129.3635859, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110027': SOURCE_INFO(source_identifier='1002110027', x=12695180.352944, y=2481132.1260737, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110035': SOURCE_INFO(source_identifier='1002110035', x=12695189.665993, y=2481102.3192534, z=1, type='non-lon-lat', activated=True, addition_info={}),
#'1002110043': SOURCE_INFO(source_identifier='1002110043', x=12695189.852014, y=2481087.049499, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110052': SOURCE_INFO(source_identifier='1002110052', x=12695190.750277, y=2481109.0095352, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110060': SOURCE_INFO(source_identifier='1002110060', x=12695187.957871, y=2481122.4172558, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110068': SOURCE_INFO(source_identifier='1002110068', x=12695189.632857, y=2481137.251188, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110077': SOURCE_INFO(source_identifier='1002110077', x=12695193.367553, y=2481116.5415274, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110085': SOURCE_INFO(source_identifier='1002110085', x=12695196.099322, y=2481103.1120196, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002120001': SOURCE_INFO(source_identifier='1002120001', x=12695206.662509, y=2481081.6088849, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002120002': SOURCE_INFO(source_identifier='1002120002', x=12695204.035373, y=2481093.856715, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002120003': SOURCE_INFO(source_identifier='1002120003', x=12695195.907734, y=2481132.5389243, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002120007': SOURCE_INFO(source_identifier='1002120007', x=12695195.09761, y=2481137.2510492, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002120004': SOURCE_INFO(source_identifier='1002120004', x=12695183.77677, y=2481148.096205, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002120005': SOURCE_INFO(source_identifier='1002120005', x=12695187.218012, y=2481105.8469989, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002120006': SOURCE_INFO(source_identifier='1002120006', x=12695189.962489, y=2481093.310101, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1002110083': SOURCE_INFO(source_identifier='1002110083', x=12695197.044459, y=2481103.3302314, z=1, type='non-lon-lat', activated=True, addition_info={}),
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
    'ALARM_VELOCITY_THRESHOLD_HIGHER': 1.54,
    'ALARM_TOLERANCE_NUMBER': 3,

    # rule 2: alarm |= within(pos, ALARM_ZONE) and step > STEP_ALARM_THRESHOLD_HIGHER
    'STEP_ALARM_THRESHOLD_HIGHER': 12,

    # rule 3: alarm |= within(pos, ALARM_ZONE) and step > STEP_ALARM_THRESHOLD_LOWER and abs(vel)
    # >ALARM_VELOCITY_THRESHOLD_LOWER
    'ALARM_VELOCITY_THRESHOLD_LOWER': 1.45,
    'STEP_ALARM_THRESHOLD_LOWER': 10,

    'ALARM_ZONE': [polygon for polyid, polygon in MAPS.items()],  # it can't be empty list or None.
}

FILTER_MODULE_PARAMS = {
    'RSSI_FILTER_ALGO_FILE': "avg_filter.py",
    'RSSI_THRESHOLD': -88,
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
    'grid_size': 0.5,
	'cal_fq': CAL_PERIOD_SEC,
}


VELOCITY_MODULE_PARAMS = {
    'VELOCITY_ALGO_FILE': "analyse_velocity.py",
    'VELOCITY_LEAST_SQUARE_WINDOW': 5,  # maximum num of position used to calculate velocity via least square
    'LOCALIZATION_CAL_PERIOD': CAL_PERIOD_SEC,
}

