<div>
    <div class="card">
        <div class="card-header">Site Creator</div>
        <div class="card-body">
            <form>
                <div class="box source"><h3>Source</h3>
                    <ul class="wrapper">
                        <li class="form-row"><label for="sourceUrl">URL <span class="required">*</span></label><input
                                    type="text" name="sourceUrl" required="">
                            <button class="btn btn-primary btn-sm btn-test valid valid">Test</button>
                        </li>
                        <li class="form-row"><label for="home">Home Page</label><input type="text" name="home"
                                                                                       disabled="">
                            <button class="btn btn-primary btn-sm btn-test">Find</button>
                            <img class="loading"
                                 src="https://cdnjs.cloudflare.com/ajax/libs/galleriffic/2.0.1/css/loader.gif" alt=""
                                 width="24" height="24" style="display: none;"></li>
                    </ul>
                </div>
                <div class="box"><h3>Destination</h3>
                    <ul class="wrapper">
                        <li class="form-row"><label for="db">Database <span class="required">*</span></label><input
                                    type="text" name="db" required="">
                            <button class="btn btn-primary btn-sm btn-test invalid">Test</button>
                        </li>
                        <li class="form-row"><label for="path">Path to WordPress</label><input type="text" name="path">
                            <button class="btn btn-primary btn-sm btn-test valid">Test</button>
                        </li>
                        <li class="form-row" style=""><label for="theme">Theme</label><select class="shift-1rem">
                                <option value="">Select</option>
                            </select></li>
                        <li class="form-row">
                            <button class="btn btn-primary btn-sm" disabled="">Submit</button>
                        </li>
                    </ul>
                </div>
            </form>
        </div>
    </div>
</div>
