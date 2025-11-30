<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <h4 class="text-lg font-semibold text-gray-900">Registration Details</h4>
            <p class="text-sm text-gray-600">Application #{{ $registration->id }}</p>
        </div>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
            {{ $registration->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
            {{ $registration->status == 'approved' ? 'bg-green-100 text-green-800' : '' }}
            {{ $registration->status == 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
            {{ ucfirst($registration->status) }}
        </span>
    </div>

    <!-- Applicant Information -->
    <div class="bg-gray-50 rounded-lg p-4">
        <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
            <i class="fas fa-user mr-2 text-blue-600"></i>Applicant Information
        </h5>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium text-gray-600">Full Name</label>
                <p class="text-gray-900">{{ $registration->user->name }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Email</label>
                <p class="text-gray-900">{{ $registration->user->email }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Registration Date</label>
                <p class="text-gray-900">{{ $registration->created_at->format('M d, Y H:i') }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Student ID</label>
                <p class="text-gray-900">{{ $registration->user->student_id ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- UKM Information -->
    <div class="bg-gray-50 rounded-lg p-4">
        <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
            <i class="fas fa-users mr-2 text-green-600"></i>UKM Information
        </h5>
        <div class="flex items-center">
            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-users text-white"></i>
            </div>
            <div>
                <h6 class="font-semibold text-gray-900">{{ $registration->ukm->name }}</h6>
                <p class="text-sm text-gray-600">
                    {{ $registration->ukm->description ? Str::limit($registration->ukm->description, 100) : 'No description available' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Application Content -->
    <div class="bg-gray-50 rounded-lg p-4">
        <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
            <i class="fas fa-file-alt mr-2 text-purple-600"></i>Application Content
        </h5>
        
        <div class="space-y-4">
            <!-- Motivation -->
            <div>
                <label class="text-sm font-medium text-gray-600 block mb-2">Motivation Letter</label>
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <p class="text-gray-700 whitespace-pre-line">{{ $registration->motivation }}</p>
                </div>
            </div>

            <!-- Experience -->
            @if($registration->experience)
            <div>
                <label class="text-sm font-medium text-gray-600 block mb-2">Relevant Experience</label>
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <p class="text-gray-700 whitespace-pre-line">{{ $registration->experience }}</p>
                </div>
            </div>
            @endif

            <!-- Skills -->
            @if($registration->skills)
            <div>
                <label class="text-sm font-medium text-gray-600 block mb-2">Skills & Talents</label>
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <p class="text-gray-700 whitespace-pre-line">{{ $registration->skills }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Status Timeline -->
    <div class="bg-gray-50 rounded-lg p-4">
        <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
            <i class="fas fa-history mr-2 text-orange-600"></i>Status Timeline
        </h5>
        
        <div class="space-y-2">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Submitted</span>
                <span class="text-sm text-gray-900">{{ $registration->created_at->format('M d, Y H:i') }}</span>
            </div>
            
            @if($registration->status != 'pending')
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">{{ ucfirst($registration->status) }} by</span>
                <span class="text-sm text-gray-900">{{ $registration->approver->name ?? 'System' }}</span>
            </div>
            
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Processed at</span>
                <span class="text-sm text-gray-900">{{ $registration->approved_at->format('M d, Y H:i') }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons for Pending Registrations -->
    @if($registration->status == 'pending')
    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
        <form action="{{ route('admin.registrations.updateStatus', $registration->id) }}" method="POST" class="inline">
            @csrf @method('PUT')
            <input type="hidden" name="status" value="rejected">
            <button type="submit" 
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200 font-medium flex items-center"
                    onclick="return confirm('Reject this registration?')">
                <i class="fas fa-times mr-2"></i> Reject
            </button>
        </form>
        
        <form action="{{ route('admin.registrations.updateStatus', $registration->id) }}" method="POST" class="inline">
            @csrf @method('PUT')
            <input type="hidden" name="status" value="approved">
            <button type="submit" 
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200 font-medium flex items-center"
                    onclick="return confirm('Approve this registration?')">
                <i class="fas fa-check mr-2"></i> Approve
            </button>
        </form>
    </div>
    @endif
</div>