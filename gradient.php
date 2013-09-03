<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

<head>
<title>CSS Gradients Demo</title>
<style type="text/css">
* {
	font-family:'Times New Roman',Times,serif;
	font-size:16px;
}
html, body {
	margin:0;
	padding:0;
	background-color:#fff;
}
div {height:1px;}
input {width:50px;}
input#go {width:auto;}
form {padding:10px;}
legend, fieldset {
	border-top:3px solid #ddd;
	border-right:3px solid #999;
	border-bottom:3px solid #777;
	border-left:3px solid #bbb;
	background-color:#eee;
}
legend {
	font-weight:bold;
	padding:2px 5px 2px 5px;
}
fieldset {
	display:inline;
	padding:10px;
	text-align:center;
}
label {font-size:80%;}
label[for='red1'], label[for='red2'] {color:#c00; margin-left:10px;}
label[for='green1'], label[for='green2'] {color:#090;}
label[for='blue1'], label[for='blue2'] {color:#00c;}
label[for='go'] {display:block; text-align:center;}
#dates {font-size:90%;}

.right {text-align:right; height:auto;}#d1 {background-color:rgb(255,255,0)}
#d2 {background-color:rgb(255,255,1)}
#d3 {background-color:rgb(255,255,2)}
#d4 {background-color:rgb(255,255,3)}
#d5 {background-color:rgb(255,255,4)}
#d6 {background-color:rgb(255,255,5)}
#d7 {background-color:rgb(255,255,6)}
#d8 {background-color:rgb(255,255,7)}
#d9 {background-color:rgb(255,255,8)}
#d10 {background-color:rgb(255,255,9)}
#d11 {background-color:rgb(255,255,10)}
#d12 {background-color:rgb(255,255,11)}
#d13 {background-color:rgb(255,255,12)}
#d14 {background-color:rgb(255,255,13)}
#d15 {background-color:rgb(255,255,14)}
#d16 {background-color:rgb(255,255,15)}
#d17 {background-color:rgb(255,255,16)}
#d18 {background-color:rgb(255,255,17)}
#d19 {background-color:rgb(255,255,18)}
#d20 {background-color:rgb(255,255,19)}
#d21 {background-color:rgb(255,255,20)}
#d22 {background-color:rgb(255,255,21)}
#d23 {background-color:rgb(255,255,22)}
#d24 {background-color:rgb(255,255,23)}
#d25 {background-color:rgb(255,255,24)}
#d26 {background-color:rgb(255,255,25)}
#d27 {background-color:rgb(255,255,26)}
#d28 {background-color:rgb(255,255,27)}
#d29 {background-color:rgb(255,255,28)}
#d30 {background-color:rgb(255,255,29)}
#d31 {background-color:rgb(255,255,30)}
#d32 {background-color:rgb(255,255,31)}
#d33 {background-color:rgb(255,255,32)}
#d34 {background-color:rgb(255,255,33)}
#d35 {background-color:rgb(255,255,34)}
#d36 {background-color:rgb(255,255,35)}
#d37 {background-color:rgb(255,255,36)}
#d38 {background-color:rgb(255,255,37)}
#d39 {background-color:rgb(255,255,38)}
#d40 {background-color:rgb(255,255,39)}
#d41 {background-color:rgb(255,255,40)}
#d42 {background-color:rgb(255,255,41)}
#d43 {background-color:rgb(255,255,42)}
#d44 {background-color:rgb(255,255,43)}
#d45 {background-color:rgb(255,255,44)}
#d46 {background-color:rgb(255,255,45)}
#d47 {background-color:rgb(255,255,46)}
#d48 {background-color:rgb(255,255,47)}
#d49 {background-color:rgb(255,255,48)}
#d50 {background-color:rgb(255,255,49)}
#d51 {background-color:rgb(255,255,50)}
#d52 {background-color:rgb(255,255,51)}
#d53 {background-color:rgb(255,255,52)}
#d54 {background-color:rgb(255,255,53)}
#d55 {background-color:rgb(255,255,54)}
#d56 {background-color:rgb(255,255,55)}
#d57 {background-color:rgb(255,255,56)}
#d58 {background-color:rgb(255,255,57)}
#d59 {background-color:rgb(255,255,58)}
#d60 {background-color:rgb(255,255,59)}
#d61 {background-color:rgb(255,255,60)}
#d62 {background-color:rgb(255,255,61)}
#d63 {background-color:rgb(255,255,62)}
#d64 {background-color:rgb(255,255,63)}
#d65 {background-color:rgb(255,255,64)}
#d66 {background-color:rgb(255,255,65)}
#d67 {background-color:rgb(255,255,66)}
#d68 {background-color:rgb(255,255,67)}
#d69 {background-color:rgb(255,255,68)}
#d70 {background-color:rgb(255,255,69)}
#d71 {background-color:rgb(255,255,70)}
#d72 {background-color:rgb(255,255,71)}
#d73 {background-color:rgb(255,255,72)}
#d74 {background-color:rgb(255,255,73)}
#d75 {background-color:rgb(255,255,74)}
#d76 {background-color:rgb(255,255,75)}
#d77 {background-color:rgb(255,255,76)}
#d78 {background-color:rgb(255,255,77)}
#d79 {background-color:rgb(255,255,78)}
#d80 {background-color:rgb(255,255,79)}
#d81 {background-color:rgb(255,255,80)}
#d82 {background-color:rgb(255,255,81)}
#d83 {background-color:rgb(255,255,82)}
#d84 {background-color:rgb(255,255,83)}
#d85 {background-color:rgb(255,255,84)}
#d86 {background-color:rgb(255,255,85)}
#d87 {background-color:rgb(255,255,86)}
#d88 {background-color:rgb(255,255,87)}
#d89 {background-color:rgb(255,255,88)}
#d90 {background-color:rgb(255,255,89)}
#d91 {background-color:rgb(255,255,90)}
#d92 {background-color:rgb(255,255,91)}
#d93 {background-color:rgb(255,255,92)}
#d94 {background-color:rgb(255,255,93)}
#d95 {background-color:rgb(255,255,94)}
#d96 {background-color:rgb(255,255,95)}
#d97 {background-color:rgb(255,255,96)}
#d98 {background-color:rgb(255,255,97)}
#d99 {background-color:rgb(255,255,98)}
#d100 {background-color:rgb(255,255,99)}
#d101 {background-color:rgb(255,255,100)}
#d102 {background-color:rgb(255,255,101)}
#d103 {background-color:rgb(255,255,102)}
#d104 {background-color:rgb(255,255,103)}
#d105 {background-color:rgb(255,255,104)}
#d106 {background-color:rgb(255,255,105)}
#d107 {background-color:rgb(255,255,106)}
#d108 {background-color:rgb(255,255,107)}
#d109 {background-color:rgb(255,255,108)}
#d110 {background-color:rgb(255,255,109)}
#d111 {background-color:rgb(255,255,110)}
#d112 {background-color:rgb(255,255,111)}
#d113 {background-color:rgb(255,255,112)}
#d114 {background-color:rgb(255,255,113)}
#d115 {background-color:rgb(255,255,114)}
#d116 {background-color:rgb(255,255,115)}
#d117 {background-color:rgb(255,255,116)}
#d118 {background-color:rgb(255,255,117)}
#d119 {background-color:rgb(255,255,118)}
#d120 {background-color:rgb(255,255,119)}
#d121 {background-color:rgb(255,255,120)}
#d122 {background-color:rgb(255,255,121)}
#d123 {background-color:rgb(255,255,122)}
#d124 {background-color:rgb(255,255,123)}
#d125 {background-color:rgb(255,255,124)}
#d126 {background-color:rgb(255,255,125)}
#d127 {background-color:rgb(255,255,126)}
#d128 {background-color:rgb(255,255,127)}
#d129 {background-color:rgb(255,255,128)}
#d130 {background-color:rgb(255,255,129)}
#d131 {background-color:rgb(255,255,130)}
#d132 {background-color:rgb(255,255,131)}
#d133 {background-color:rgb(255,255,132)}
#d134 {background-color:rgb(255,255,133)}
#d135 {background-color:rgb(255,255,134)}
#d136 {background-color:rgb(255,255,135)}
#d137 {background-color:rgb(255,255,136)}
#d138 {background-color:rgb(255,255,137)}
#d139 {background-color:rgb(255,255,138)}
#d140 {background-color:rgb(255,255,139)}
#d141 {background-color:rgb(255,255,140)}
#d142 {background-color:rgb(255,255,141)}
#d143 {background-color:rgb(255,255,142)}
#d144 {background-color:rgb(255,255,143)}
#d145 {background-color:rgb(255,255,144)}
#d146 {background-color:rgb(255,255,145)}
#d147 {background-color:rgb(255,255,146)}
#d148 {background-color:rgb(255,255,147)}
#d149 {background-color:rgb(255,255,148)}
#d150 {background-color:rgb(255,255,149)}
#d151 {background-color:rgb(255,255,150)}
#d152 {background-color:rgb(255,255,151)}
#d153 {background-color:rgb(255,255,152)}
#d154 {background-color:rgb(255,255,153)}
#d155 {background-color:rgb(255,255,154)}
#d156 {background-color:rgb(255,255,155)}
#d157 {background-color:rgb(255,255,156)}
#d158 {background-color:rgb(255,255,157)}
#d159 {background-color:rgb(255,255,158)}
#d160 {background-color:rgb(255,255,159)}
#d161 {background-color:rgb(255,255,160)}
#d162 {background-color:rgb(255,255,161)}
#d163 {background-color:rgb(255,255,162)}
#d164 {background-color:rgb(255,255,163)}
#d165 {background-color:rgb(255,255,164)}
#d166 {background-color:rgb(255,255,165)}
#d167 {background-color:rgb(255,255,166)}
#d168 {background-color:rgb(255,255,167)}
#d169 {background-color:rgb(255,255,168)}
#d170 {background-color:rgb(255,255,169)}
#d171 {background-color:rgb(255,255,170)}
#d172 {background-color:rgb(255,255,171)}
#d173 {background-color:rgb(255,255,172)}
#d174 {background-color:rgb(255,255,173)}
#d175 {background-color:rgb(255,255,174)}
#d176 {background-color:rgb(255,255,175)}
#d177 {background-color:rgb(255,255,176)}
#d178 {background-color:rgb(255,255,177)}
#d179 {background-color:rgb(255,255,178)}
#d180 {background-color:rgb(255,255,179)}
#d181 {background-color:rgb(255,255,180)}
#d182 {background-color:rgb(255,255,181)}
#d183 {background-color:rgb(255,255,182)}
#d184 {background-color:rgb(255,255,183)}
#d185 {background-color:rgb(255,255,184)}
#d186 {background-color:rgb(255,255,185)}
#d187 {background-color:rgb(255,255,186)}
#d188 {background-color:rgb(255,255,187)}
#d189 {background-color:rgb(255,255,188)}
#d190 {background-color:rgb(255,255,189)}
#d191 {background-color:rgb(255,255,190)}
#d192 {background-color:rgb(255,255,191)}
#d193 {background-color:rgb(255,255,192)}
#d194 {background-color:rgb(255,255,193)}
#d195 {background-color:rgb(255,255,194)}
#d196 {background-color:rgb(255,255,195)}
#d197 {background-color:rgb(255,255,196)}
#d198 {background-color:rgb(255,255,197)}
#d199 {background-color:rgb(255,255,198)}
#d200 {background-color:rgb(255,255,199)}
#d201 {background-color:rgb(255,255,200)}
#d202 {background-color:rgb(255,255,201)}
#d203 {background-color:rgb(255,255,202)}
#d204 {background-color:rgb(255,255,203)}
#d205 {background-color:rgb(255,255,204)}
#d206 {background-color:rgb(255,255,205)}
#d207 {background-color:rgb(255,255,206)}
#d208 {background-color:rgb(255,255,207)}
#d209 {background-color:rgb(255,255,208)}
#d210 {background-color:rgb(255,255,209)}
#d211 {background-color:rgb(255,255,210)}
#d212 {background-color:rgb(255,255,211)}
#d213 {background-color:rgb(255,255,212)}
#d214 {background-color:rgb(255,255,213)}
#d215 {background-color:rgb(255,255,214)}
#d216 {background-color:rgb(255,255,215)}
#d217 {background-color:rgb(255,255,216)}
#d218 {background-color:rgb(255,255,217)}
#d219 {background-color:rgb(255,255,218)}
#d220 {background-color:rgb(255,255,219)}
#d221 {background-color:rgb(255,255,220)}
#d222 {background-color:rgb(255,255,221)}
#d223 {background-color:rgb(255,255,222)}
#d224 {background-color:rgb(255,255,223)}
#d225 {background-color:rgb(255,255,224)}
#d226 {background-color:rgb(255,255,225)}
#d227 {background-color:rgb(255,255,226)}
#d228 {background-color:rgb(255,255,227)}
#d229 {background-color:rgb(255,255,228)}
#d230 {background-color:rgb(255,255,229)}
#d231 {background-color:rgb(255,255,230)}
#d232 {background-color:rgb(255,255,231)}
#d233 {background-color:rgb(255,255,232)}
#d234 {background-color:rgb(255,255,233)}
#d235 {background-color:rgb(255,255,234)}
#d236 {background-color:rgb(255,255,235)}
#d237 {background-color:rgb(255,255,236)}
#d238 {background-color:rgb(255,255,237)}
#d239 {background-color:rgb(255,255,238)}
#d240 {background-color:rgb(255,255,239)}
#d241 {background-color:rgb(255,255,240)}
#d242 {background-color:rgb(255,255,241)}
#d243 {background-color:rgb(255,255,242)}
#d244 {background-color:rgb(255,255,243)}
#d245 {background-color:rgb(255,255,244)}
#d246 {background-color:rgb(255,255,245)}
#d247 {background-color:rgb(255,255,246)}
#d248 {background-color:rgb(255,255,247)}
#d249 {background-color:rgb(255,255,248)}
#d250 {background-color:rgb(255,255,249)}
#d251 {background-color:rgb(255,255,250)}
#d252 {background-color:rgb(255,255,251)}
#d253 {background-color:rgb(255,255,252)}
#d254 {background-color:rgb(255,255,253)}
#d255 {background-color:rgb(255,255,254)}
</style>
</head>

<body>
<div id="d1"><!-- --></div>
<div id="d2"><!-- --></div>
<div id="d3"><!-- --></div>
<div id="d4"><!-- --></div>
<div id="d5"><!-- --></div>
<div id="d6"><!-- --></div>
<div id="d7"><!-- --></div>
<div id="d8"><!-- --></div>
<div id="d9"><!-- --></div>
<div id="d10"><!-- --></div>
<div id="d11"><!-- --></div>
<div id="d12"><!-- --></div>
<div id="d13"><!-- --></div>
<div id="d14"><!-- --></div>
<div id="d15"><!-- --></div>
<div id="d16"><!-- --></div>
<div id="d17"><!-- --></div>
<div id="d18"><!-- --></div>
<div id="d19"><!-- --></div>
<div id="d20"><!-- --></div>
<div id="d21"><!-- --></div>
<div id="d22"><!-- --></div>
<div id="d23"><!-- --></div>
<div id="d24"><!-- --></div>
<div id="d25"><!-- --></div>
<div id="d26"><!-- --></div>
<div id="d27"><!-- --></div>
<div id="d28"><!-- --></div>
<div id="d29"><!-- --></div>
<div id="d30"><!-- --></div>
<div id="d31"><!-- --></div>
<div id="d32"><!-- --></div>
<div id="d33"><!-- --></div>
<div id="d34"><!-- --></div>
<div id="d35"><!-- --></div>
<div id="d36"><!-- --></div>
<div id="d37"><!-- --></div>
<div id="d38"><!-- --></div>
<div id="d39"><!-- --></div>
<div id="d40"><!-- --></div>
<div id="d41"><!-- --></div>
<div id="d42"><!-- --></div>
<div id="d43"><!-- --></div>
<div id="d44"><!-- --></div>
<div id="d45"><!-- --></div>
<div id="d46"><!-- --></div>
<div id="d47"><!-- --></div>
<div id="d48"><!-- --></div>
<div id="d49"><!-- --></div>
<div id="d50"><!-- --></div>
<div id="d51"><!-- --></div>
<div id="d52"><!-- --></div>
<div id="d53"><!-- --></div>
<div id="d54"><!-- --></div>
<div id="d55"><!-- --></div>
<div id="d56"><!-- --></div>
<div id="d57"><!-- --></div>
<div id="d58"><!-- --></div>
<div id="d59"><!-- --></div>
<div id="d60"><!-- --></div>
<div id="d61"><!-- --></div>
<div id="d62"><!-- --></div>
<div id="d63"><!-- --></div>
<div id="d64"><!-- --></div>
<div id="d65"><!-- --></div>
<div id="d66"><!-- --></div>
<div id="d67"><!-- --></div>
<div id="d68"><!-- --></div>
<div id="d69"><!-- --></div>
<div id="d70"><!-- --></div>
<div id="d71"><!-- --></div>
<div id="d72"><!-- --></div>
<div id="d73"><!-- --></div>
<div id="d74"><!-- --></div>
<div id="d75"><!-- --></div>
<div id="d76"><!-- --></div>
<div id="d77"><!-- --></div>
<div id="d78"><!-- --></div>
<div id="d79"><!-- --></div>
<div id="d80"><!-- --></div>
<div id="d81"><!-- --></div>
<div id="d82"><!-- --></div>
<div id="d83"><!-- --></div>
<div id="d84"><!-- --></div>
<div id="d85"><!-- --></div>
<div id="d86"><!-- --></div>
<div id="d87"><!-- --></div>
<div id="d88"><!-- --></div>
<div id="d89"><!-- --></div>
<div id="d90"><!-- --></div>
<div id="d91"><!-- --></div>
<div id="d92"><!-- --></div>
<div id="d93"><!-- --></div>
<div id="d94"><!-- --></div>
<div id="d95"><!-- --></div>
<div id="d96"><!-- --></div>
<div id="d97"><!-- --></div>
<div id="d98"><!-- --></div>
<div id="d99"><!-- --></div>
<div id="d100"><!-- --></div>
<div id="d101"><!-- --></div>
<div id="d102"><!-- --></div>
<div id="d103"><!-- --></div>
<div id="d104"><!-- --></div>
<div id="d105"><!-- --></div>
<div id="d106"><!-- --></div>
<div id="d107"><!-- --></div>
<div id="d108"><!-- --></div>
<div id="d109"><!-- --></div>
<div id="d110"><!-- --></div>
<div id="d111"><!-- --></div>
<div id="d112"><!-- --></div>
<div id="d113"><!-- --></div>
<div id="d114"><!-- --></div>
<div id="d115"><!-- --></div>
<div id="d116"><!-- --></div>
<div id="d117"><!-- --></div>
<div id="d118"><!-- --></div>
<div id="d119"><!-- --></div>
<div id="d120"><!-- --></div>
<div id="d121"><!-- --></div>
<div id="d122"><!-- --></div>
<div id="d123"><!-- --></div>
<div id="d124"><!-- --></div>
<div id="d125"><!-- --></div>
<div id="d126"><!-- --></div>
<div id="d127"><!-- --></div>
<div id="d128"><!-- --></div>
<div id="d129"><!-- --></div>
<div id="d130"><!-- --></div>
<div id="d131"><!-- --></div>
<div id="d132"><!-- --></div>
<div id="d133"><!-- --></div>
<div id="d134"><!-- --></div>
<div id="d135"><!-- --></div>
<div id="d136"><!-- --></div>
<div id="d137"><!-- --></div>
<div id="d138"><!-- --></div>
<div id="d139"><!-- --></div>
<div id="d140"><!-- --></div>
<div id="d141"><!-- --></div>
<div id="d142"><!-- --></div>
<div id="d143"><!-- --></div>
<div id="d144"><!-- --></div>
<div id="d145"><!-- --></div>
<div id="d146"><!-- --></div>
<div id="d147"><!-- --></div>
<div id="d148"><!-- --></div>
<div id="d149"><!-- --></div>
<div id="d150"><!-- --></div>
<div id="d151"><!-- --></div>
<div id="d152"><!-- --></div>
<div id="d153"><!-- --></div>
<div id="d154"><!-- --></div>
<div id="d155"><!-- --></div>
<div id="d156"><!-- --></div>
<div id="d157"><!-- --></div>
<div id="d158"><!-- --></div>
<div id="d159"><!-- --></div>
<div id="d160"><!-- --></div>
<div id="d161"><!-- --></div>
<div id="d162"><!-- --></div>
<div id="d163"><!-- --></div>
<div id="d164"><!-- --></div>
<div id="d165"><!-- --></div>
<div id="d166"><!-- --></div>
<div id="d167"><!-- --></div>
<div id="d168"><!-- --></div>
<div id="d169"><!-- --></div>
<div id="d170"><!-- --></div>
<div id="d171"><!-- --></div>
<div id="d172"><!-- --></div>
<div id="d173"><!-- --></div>
<div id="d174"><!-- --></div>
<div id="d175"><!-- --></div>
<div id="d176"><!-- --></div>
<div id="d177"><!-- --></div>
<div id="d178"><!-- --></div>
<div id="d179"><!-- --></div>
<div id="d180"><!-- --></div>
<div id="d181"><!-- --></div>
<div id="d182"><!-- --></div>
<div id="d183"><!-- --></div>
<div id="d184"><!-- --></div>
<div id="d185"><!-- --></div>
<div id="d186"><!-- --></div>
<div id="d187"><!-- --></div>
<div id="d188"><!-- --></div>
<div id="d189"><!-- --></div>
<div id="d190"><!-- --></div>
<div id="d191"><!-- --></div>
<div id="d192"><!-- --></div>
<div id="d193"><!-- --></div>
<div id="d194"><!-- --></div>
<div id="d195"><!-- --></div>
<div id="d196"><!-- --></div>
<div id="d197"><!-- --></div>
<div id="d198"><!-- --></div>
<div id="d199"><!-- --></div>
<div id="d200"><!-- --></div>
<div id="d201"><!-- --></div>
<div id="d202"><!-- --></div>
<div id="d203"><!-- --></div>
<div id="d204"><!-- --></div>
<div id="d205"><!-- --></div>
<div id="d206"><!-- --></div>
<div id="d207"><!-- --></div>
<div id="d208"><!-- --></div>
<div id="d209"><!-- --></div>
<div id="d210"><!-- --></div>
<div id="d211"><!-- --></div>
<div id="d212"><!-- --></div>
<div id="d213"><!-- --></div>
<div id="d214"><!-- --></div>
<div id="d215"><!-- --></div>
<div id="d216"><!-- --></div>
<div id="d217"><!-- --></div>
<div id="d218"><!-- --></div>
<div id="d219"><!-- --></div>
<div id="d220"><!-- --></div>
<div id="d221"><!-- --></div>
<div id="d222"><!-- --></div>
<div id="d223"><!-- --></div>
<div id="d224"><!-- --></div>
<div id="d225"><!-- --></div>
<div id="d226"><!-- --></div>
<div id="d227"><!-- --></div>
<div id="d228"><!-- --></div>
<div id="d229"><!-- --></div>
<div id="d230"><!-- --></div>
<div id="d231"><!-- --></div>
<div id="d232"><!-- --></div>
<div id="d233"><!-- --></div>
<div id="d234"><!-- --></div>
<div id="d235"><!-- --></div>
<div id="d236"><!-- --></div>
<div id="d237"><!-- --></div>
<div id="d238"><!-- --></div>
<div id="d239"><!-- --></div>
<div id="d240"><!-- --></div>
<div id="d241"><!-- --></div>
<div id="d242"><!-- --></div>
<div id="d243"><!-- --></div>
<div id="d244"><!-- --></div>
<div id="d245"><!-- --></div>
<div id="d246"><!-- --></div>
<div id="d247"><!-- --></div>
<div id="d248"><!-- --></div>
<div id="d249"><!-- --></div>
<div id="d250"><!-- --></div>
<div id="d251"><!-- --></div>
<div id="d252"><!-- --></div>
<div id="d253"><!-- --></div>
<div id="d254"><!-- --></div>
<div id="d255"><!-- --></div>

<form action="" method="post">
<fieldset>
<legend>CSS Gradients Demo</legend>
<div class="right">
<br />Start Colour: <label for="red1"> RED: <input name="red1" id="red1" maxlength="3" value="255" /></label><label for="green1"> GREEN: <input name="green1" id="green1" maxlength="3" value="255" /></label><label for="blue1"> BLUE:&nbsp;&nbsp;<input name="blue1" id="blue1" maxlength="3" value="0" /></label>
<br />End Colour: <label for="red2"> RED: <input name="red2" id="red2" maxlength="3" value="255" /></label><label for="green2"> GREEN: <input name="green2" id="green2" maxlength="3" value="255" /></label><label for="blue2"> BLUE:&nbsp;&nbsp;<input name="blue2" id="blue2" maxlength="3" value="255" /></label>
</div>
<br /><label for="go"><input type="submit" name="go" id="go" value=" Generate gradient! " /></label>
<!--[if IE]><br /><![endif]-->
<br /><span id="dates">By <a href="http://www.designdetector.com">Chris Hester</a> 5th September 2005 &middot; Updated: 13 October 2005
<br />
<br /><a href="http://www.designdetector.com/2005/09/css-gradients-demo.php" title="About this demo">INFO</a> &middot; <a href="http://www.designdetector.com/demos/css-gradients-demo-2.php" title="The next demo in this series">Demo 2</a></span>
</fieldset>
</form></body>
</html>