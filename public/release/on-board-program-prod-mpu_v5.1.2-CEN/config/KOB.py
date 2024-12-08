# on-board-program-from-pi version of different sites
from collections import namedtuple
from enum import Enum
from config.common import SOURCE_INFO
import os

MAPS = {
11:[[12700015.413671117,2482375.552167143,1],[12700015.94576509,2482370.007513109,1],[12700012.283617163,2482369.703480874,1],[12700011.78926498,2482375.210386823,1]],
12:[[12700022.082912838,2482364.8180851196,1],[12700012.91374026,2482364.0631373646,1],[12700014.013080088,2482352.759290652,1],[12700023.235815395,2482353.657041216,1]],
13:[[12700019.456996568,2482370.5372897345,1],[12700020.085828332,2482364.323348147,1],[12700012.869578984,2482363.6666391934,1],[12700012.353714192,2482369.9056640817,1]],
14:[[12700023.588189643,2482355.12170561,1],[12700018.609083325,2482353.972154764,1],[12700021.54169579,2482324.914337019,1],[12700026.668999327,2482325.2796811135,1]],
15:[[12700021.166495005,2482333.3052974343,1],[12700021.812825475,2482326.2376501616,1],[12700013.15999456,2482325.46331511,1],[12700012.538680755,2482332.532413477,1]],
16:[[12700019.176249817,2482295.0916364617,1],[12700017.77273695,2482296.192186267,1],[12700018.909913745,2482283.9722623844,1],[12700020.212320572,2482284.0689115473,1]],
17:[[12700028.617895028,2482187.301477294,1],[12700037.263343997,2482187.9813741846,1],[12700039.516932698,2482163.3323509856,1],[12700030.912757182,2482162.552407949,1]],
18:[[12700026.656666119,2482325.4277320784,1],[12700027.488102201,2482316.450198067,1],[12700023.177098736,2482316.0784589695,1],[12700022.19480516,2482325.1591183143,1]],
19:[[12700014.914149543,2482325.8834468313,1],[12700017.851572137,2482294.164191035,1],[12700016.109846612,2482293.988611824,1],[12700013.055517817,2482325.743656122,1]],
110:[[12700020.2269661,2482284.500289437,1],[12700017.091480436,2482284.2417028625,1],[12700018.540004909,2482267.8671815833,1],[12700021.904323008,2482268.159977706,1]],
111:[[12700017.829659656,2482296.222219603,1],[12700017.476837724,2482296.1936652446,1],[12700017.666220346,2482293.9934019423,1],[12700018.078000102,2482293.992109358,1]],
112:[[12700015.988824414,2482280.1874534544,1],[12700014.199394636,2482280.048067804,1],[12700014.778161615,2482273.610981697,1],[12700016.597982557,2482273.7859859606,1]],
113:[[12700017.917776449,2482277.257252575,1],[12700016.114803426,2482277.104239865,1],[12700016.353537615,2482273.7886190484,1],[12700018.2073779,2482273.916237002,1]],
114:[[12700020.923278825,2482268.2760267975,1],[12700018.54298213,2482268.176607916,1],[12700021.853717154,2482232.1413223324,1],[12700024.048318371,2482234.7619592566,1]],
115:[[12700023.347971043,2482234.6686343993,1],[12700021.681679606,2482234.5711985524,1],[12700025.79458339,2482189.8534561223,1],[12700027.359923787,2482189.9790594047,1]],
116:[[12700036.45296125,2482187.632564785,1],[12700035.644946313,2482196.580072701,1],[12700028.57383408,2482196.0266571497,1],[12700029.324851962,2482187.0589747564,1]],
117:[[12700028.738275556,2482196.0848834678,1],[12700026.639997642,2482195.9375243303,1],[12700027.14107665,2482189.869266912,1],[12700029.287542433,2482190.0205260143,1]],
}

SOURCE_INFO_DICT = {
'1001110001': SOURCE_INFO(source_identifier='1001110001', x=12700015.56931, y=2482375.1856452, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110002': SOURCE_INFO(source_identifier='1001110002', x=12700015.95225, y=2482370.5431495, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110004': SOURCE_INFO(source_identifier='1001110004', x=12700019.63883, y=2482370.5152831, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110003': SOURCE_INFO(source_identifier='1001110003', x=12700016.424669, y=2482370.2724665, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110005': SOURCE_INFO(source_identifier='1001110005', x=12700020.139441, y=2482364.8264469, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110006': SOURCE_INFO(source_identifier='1001110006', x=12700022.57735, y=2482361.4061225, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110007': SOURCE_INFO(source_identifier='1001110007', x=12700023.188227, y=2482356.3320299, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110008': SOURCE_INFO(source_identifier='1001110008', x=12700024.322295, y=2482352.7320301, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110009': SOURCE_INFO(source_identifier='1001110009', x=12700025.101492, y=2482342.7445805, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110010': SOURCE_INFO(source_identifier='1001110010', x=12700025.937458, y=2482332.3228298, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110011': SOURCE_INFO(source_identifier='1001110011', x=12700026.510382, y=2482327.0927469, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110012': SOURCE_INFO(source_identifier='1001110012', x=12700027.046289, y=2482321.8221713, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110013': SOURCE_INFO(source_identifier='1001110013', x=12700027.489114, y=2482316.9421763, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110014': SOURCE_INFO(source_identifier='1001110014', x=12700019.178397, y=2482347.4536328, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110015': SOURCE_INFO(source_identifier='1001110015', x=12700020.069391, y=2482338.2306771, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110016': SOURCE_INFO(source_identifier='1001110016', x=12700020.46835, y=2482333.8144882, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110017': SOURCE_INFO(source_identifier='1001110017', x=12700020.129452, y=2482333.4408756, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110018': SOURCE_INFO(source_identifier='1001110018', x=12700017.592689, y=2482333.1920647, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110019': SOURCE_INFO(source_identifier='1001110019', x=12700014.263863, y=2482332.974586, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110020': SOURCE_INFO(source_identifier='1001110020', x=12700012.594436, y=2482331.0393142, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110021': SOURCE_INFO(source_identifier='1001110021', x=12700012.136748, y=2482325.640907, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110022': SOURCE_INFO(source_identifier='1001110022', x=12700013.560369, y=2482320.5928581, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110023': SOURCE_INFO(source_identifier='1001110023', x=12700012.839794, y=2482317.1249385, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110024': SOURCE_INFO(source_identifier='1001110024', x=12700013.231543, y=2482312.9828189, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110025': SOURCE_INFO(source_identifier='1001110025', x=12700014.550779, y=2482310.1208797, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110026': SOURCE_INFO(source_identifier='1001110026', x=12700013.814836, y=2482307.1513975, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110027': SOURCE_INFO(source_identifier='1001110027', x=12700014.237112, y=2482302.4712561, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110028': SOURCE_INFO(source_identifier='1001110028', x=12700015.55083, y=2482299.6676548, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110029': SOURCE_INFO(source_identifier='1001110029', x=12700016.443939, y=2482293.9200887, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110030': SOURCE_INFO(source_identifier='1001110030', x=12700017.9934, y=2482293.6633778, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110031': SOURCE_INFO(source_identifier='1001110031', x=12700018.752459, y=2482284.9656408, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110032': SOURCE_INFO(source_identifier='1001110032', x=12700017.573036, y=2482278.2861126, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110033': SOURCE_INFO(source_identifier='1001110033', x=12700016.000105, y=2482273.0692803, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110034': SOURCE_INFO(source_identifier='1001110034', x=12700018.607044, y=2482267.3428347, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110035': SOURCE_INFO(source_identifier='1001110035', x=12700017.761521, y=2482264.1881165, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110036': SOURCE_INFO(source_identifier='1001110036', x=12700018.197412, y=2482259.5792633, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110037': SOURCE_INFO(source_identifier='1001110037', x=12700019.500096, y=2482256.8923341, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110038': SOURCE_INFO(source_identifier='1001110038', x=12700018.967274, y=2482251.6335086, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110039': SOURCE_INFO(source_identifier='1001110039', x=12700020.475683, y=2482246.4354075, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110040': SOURCE_INFO(source_identifier='1001110040', x=12700019.956499, y=2482240.8380618, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110041': SOURCE_INFO(source_identifier='1001110041', x=12700021.475941, y=2482235.5232769, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110042': SOURCE_INFO(source_identifier='1001110042', x=12700020.788027, y=2482231.8176964, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110043': SOURCE_INFO(source_identifier='1001110043', x=12700021.286399, y=2482227.2119935, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110044': SOURCE_INFO(source_identifier='1001110044', x=12700022.48532, y=2482224.4923636, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110045': SOURCE_INFO(source_identifier='1001110045', x=12700021.724403, y=2482221.5850822, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110046': SOURCE_INFO(source_identifier='1001110046', x=12700022.175478, y=2482216.5694238, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110047': SOURCE_INFO(source_identifier='1001110047', x=12700023.461661, y=2482214.0289381, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110048': SOURCE_INFO(source_identifier='1001110048', x=12700022.736778, y=2482211.0031824, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110049': SOURCE_INFO(source_identifier='1001110049', x=12700023.166614, y=2482206.4583312, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110050': SOURCE_INFO(source_identifier='1001110050', x=12700024.445442, y=2482203.6269896, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110051': SOURCE_INFO(source_identifier='1001110051', x=12700023.729639, y=2482200.5052139, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110052': SOURCE_INFO(source_identifier='1001110052', x=12700024.099362, y=2482195.8587384, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110053': SOURCE_INFO(source_identifier='1001110053', x=12700025.432252, y=2482193.1930253, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110054': SOURCE_INFO(source_identifier='1001110054', x=12700028.646864, y=2482188.3252793, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110055': SOURCE_INFO(source_identifier='1001110055', x=12700029.125804, y=2482187.8826434, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110056': SOURCE_INFO(source_identifier='1001110056', x=12700029.146525, y=2482181.5245484, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110057': SOURCE_INFO(source_identifier='1001110057', x=12700029.656789, y=2482175.7606579, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110058': SOURCE_INFO(source_identifier='1001110058', x=12700030.23063, y=2482169.5002894, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110059': SOURCE_INFO(source_identifier='1001110059', x=12700030.723033, y=2482164.4816394, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110060': SOURCE_INFO(source_identifier='1001110060', x=12700039.616511, y=2482163.4426587, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110061': SOURCE_INFO(source_identifier='1001110061', x=12700038.872819, y=2482170.8096305, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110062': SOURCE_INFO(source_identifier='1001110062', x=12700038.177018, y=2482178.2939475, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110063': SOURCE_INFO(source_identifier='1001110063', x=12700037.736465, y=2482183.4486117, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001110064': SOURCE_INFO(source_identifier='1001110064', x=12700037.592074, y=2482187.9540238, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001120001': SOURCE_INFO(source_identifier='1001120001', x=12700017.598347, y=2482375.6444885, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001120002': SOURCE_INFO(source_identifier='1001120002', x=12700025.799743, y=2482353.0356356, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001120003': SOURCE_INFO(source_identifier='1001120003', x=12700026.7809, y=2482342.6615363, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001120004': SOURCE_INFO(source_identifier='1001120004', x=12700028.395068, y=2482327.0888097, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001120005': SOURCE_INFO(source_identifier='1001120005', x=12700018.019901, y=2482335.8508038, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001120006': SOURCE_INFO(source_identifier='1001120006', x=12700026.484382, y=2482182.7777661, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001120007': SOURCE_INFO(source_identifier='1001120007', x=12700027.019945, y=2482181.2092191, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001120008': SOURCE_INFO(source_identifier='1001120008', x=12700027.367605, y=2482172.2107613, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001120009': SOURCE_INFO(source_identifier='1001120009', x=12700041.936429, y=2482163.4824328, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001120010': SOURCE_INFO(source_identifier='1001120010', x=12700040.996523, y=2482173.829937, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001120011': SOURCE_INFO(source_identifier='1001120011', x=12700040.815017, y=2482178.6151002, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001120012': SOURCE_INFO(source_identifier='1001120012', x=12700040.014833, y=2482184.2098694, z=1, type='non-lon-lat', activated=True, addition_info={}),
'1001120013': SOURCE_INFO(source_identifier='1001120013', x=12700016.526631, y=2482289.2458167, z=1, type='non-lon-lat', activated=True, addition_info={}),
}
# [data_pool]
# maximum time range inside a current data pool
window_size_in_sec = 2.
# time gap between two updates in data pool(second)
sec_per_update = 1

# [TRACKING]
CAL_PERIOD_SEC = 1.2  # time gap between two calculations (second)

ALARM_PARAMS = {
	'ALARM_PIN': 17,
	# rule 1: alarm |= within(pos, ALARM_ZONE) and recent(vel_history) > ALARM_VELOCITY_THRESHOLD_HIGHER
	'ALARM_VELOCITY_THRESHOLD_HIGHER': 1.55,
	'ALARM_TOLERANCE_NUMBER': 7,

	# rule 2: alarm |= within(pos, ALARM_ZONE) and step > STEP_ALARM_THRESHOLD_HIGHER
	'STEP_ALARM_THRESHOLD_HIGHER': 12,

	# rule 3: alarm |= within(pos, ALARM_ZONE) and step > STEP_ALARM_THRESHOLD_LOWER and abs(vel) >ALARM_VELOCITY_THRESHOLD_LOWER
	'ALARM_VELOCITY_THRESHOLD_LOWER': 1.45,
	'STEP_ALARM_THRESHOLD_LOWER': 9,

	'ALARM_ZONE': [polygon for polyid, polygon in MAPS.items()],  # it can't be empty list or None.
}

FILTER_MODULE_PARAMS = {
	'RSSI_FILTER_ALGO_FILE': "avg_filter.py",
	'RSSI_THRESHOLD': -85,
}

LOCALIZATION_MODULE_PARAMS = {
	'LOCALIZATION_ALGO_FILE': "new_localization_pf.cpython-37m-arm-linux-gnueabihf.so",
	'num_of_particles': 700,
	'max_vel': 2.,  # maximum speed of human, unit in m/s
	'obs_model_mean': [-76.0656, 1.3871],  # mean and variance of P(rssi, distance)
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
