@extends('frontend.layout.app')

@section('meta_title', 'Terms & Conditions | MAAC Durgapur')
@section('meta_description', 'Read the terms and conditions, privacy policy, and terms of use for the MAAC Durgapur website.')
@section('canonical_url', url()->current())
@section('og_title', 'Terms & Conditions | MAAC Durgapur')
@section('og_description', 'Read the terms and conditions, privacy policy, and terms of use for the MAAC Durgapur website.')

@section('custom_css')
<style>
    .tc-section {
        padding: 150px 0 80px;
    }
    .tc-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 15px;
        font-family: 'Poppins', sans-serif;
    }
    .tc-container h3 {
        color: #e50000;
        margin-top: 30px;
        margin-bottom: 15px;
    }
    .tc-container p {
        line-height: 1.8;
        color: #444;
        margin-bottom: 15px;
    }
</style>
@endsection

@section('content')
<section class="tc-section">
    <div class="tc-container">
        <h3>Terms and Conditions:</h3>
        <p>Welcome to the MAAC Animation Durgapur website. By accessing and using this website, you agree to comply with and be bound by the following terms and conditions of use. If you disagree with any part of these terms and conditions, please do not use our website.

        The content of the pages of this website is for your general information and use only. It is subject to change without notice.

        Neither we nor any third parties provide any warranty or guarantee as to the accuracy, timeliness, performance, completeness, or suitability of the information and materials found or offered on this website for any particular purpose. You acknowledge that such information and materials may contain inaccuracies or errors, and we expressly exclude liability for any such inaccuracies or errors to the fullest extent permitted by law.

        Your use of any information or materials on this website is entirely at your own risk, for which we shall not be liable. It shall be your own responsibility to ensure that any products, services, or information available through this website meet your specific requirements.

        This website contains material which is owned by or licensed to us. This material includes, but is not limited to, the design, layout, look, appearance, and graphics. Reproduction is prohibited other than in accordance with the copyright notice, which forms part of these terms and conditions.

        All trademarks reproduced in this website which is not the property of, or licensed to, the operator are acknowledged on the website.

        Unauthorized use of this website may give rise to a claim for damages and/or be a criminal offence.

        From time to time, this website may also include links to other websites. These links are provided for your convenience to provide further information. They do not signify that we endorse the website(s). We have no responsibility for the content of the linked website(s).

        Your use of this website and any dispute arising out of such use of the website is subject to the laws of India.
        </p>
        <h3>Privacy Policy:</h3>
        <p>At MAAC Animation Durgapur, we are committed to protecting your privacy. This Privacy Policy outlines how we collect, use, and disclose information when you visit our website or interact with our services.</p>

        <h3>Information We Collect:</h3>
    </div>
</section>
@endsection